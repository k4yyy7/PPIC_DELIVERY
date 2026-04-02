<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverItem;
use App\Models\ArmadaItem;
use App\Models\DailyReport;
use App\Models\User;
use App\Models\Driver;
use App\Models\Car;
use App\Models\Dokument;
use App\Models\Environment;
use App\Models\Safety;
use App\Models\UserActiveDriver;
use App\Exports\AdminMonthlyReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Remove the specified report from storage.
     */
    public function destroyMonthlyReport($id)
    {
        $report = \App\Models\DailyReport::find($id);
        if (!$report) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
        }
        $report->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }

    public function index()
    {
        // Mengambil jumlah driver dari tabel Driver
        $totalDriver = Driver::count();
        // Mengambil jumlah armada/mobil
        $totalArmada = Car::count();
        // Mengambil total item (Driver Item + Armada Item + Dokument + Environment + Safety)
        $totalItem = DriverItem::count() + ArmadaItem::count() + Dokument::count() + Environment::count() + Safety::count();
        // Mengambil laporan hari ini
        $laporanHariIni = DailyReport::whereDate('date', now()->toDateString())
            ->count();

        // User Login Statistics - Per hari selama 7 hari terakhir
        $days = 7;
        $dates = [];
        $loginCounts = [];
        $newVisitorsCounts = [];
        $activeUsersCounts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->format('M d');
            $dates[] = $dateString;

            // Hitung user yang login hari itu (berdasarkan last_login_at)
            $loginCount = User::where('role', 'user')
                ->whereDate('last_login_at', $date->toDateString())
                ->count();
            $loginCounts[] = $loginCount;

            // Hitung new visitors (user baru yang daftar)
            $newVisitors = User::where('role', 'user')
                ->whereDate('created_at', $date->toDateString())
                ->count();
            $newVisitorsCounts[] = $newVisitors;

            // Hitung active users (yang melakukan update/aktivitas hari itu)
            $activeUsers = User::where('role', 'user')
                ->whereDate('updated_at', $date->toDateString())
                ->count();
            $activeUsersCounts[] = $activeUsers;
        }

        $chartData = [
            'dates' => $dates,
            'loginCounts' => $loginCounts,
            'newVisitorsCounts' => $newVisitorsCounts,
            'activeUsersCounts' => $activeUsersCounts
        ];

        // Get live driver feed data for today
        $driverFeed = $this->getDriverFeedData();

        return view('admin.dashboard-admin', compact('totalDriver', 'totalArmada', 'totalItem', 'laporanHariIni', 'chartData', 'driverFeed'));
    }

    /**
     * Get driver feed data for live monitoring
     * MODIFIED: Group by User, show multiple statuses in one row.
     */
    private function getDriverFeedData()
    {
        $today = now()->toDateString();

        // 1. Ambil SEMUA laporan hari ini, urutkan terbaru
        $reportsToday = DailyReport::whereDate('date', $today)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Group laporan berdasarkan User ID
        $groupedReports = $reportsToday->groupBy('user_id');

        // 3. Siapkan data untuk User yang SUDAH melapor
        $feedData = [];

        // Ambil semua ID user yang sudah melapor untuk query Active Driver sekali jalan
        $userIdsReported = $groupedReports->keys()->toArray();

        // Query Active Driver untuk user yang melapor
        $activeDrivers = UserActiveDriver::whereIn('user_id', $userIdsReported)
            ->where('date', $today)
            ->with('driver')
            ->get()
            ->keyBy('user_id');

        foreach ($groupedReports as $userId => $reports) {
            $firstReport = $reports->first(); // Ambil info user dari laporan pertama
            $user = $firstReport->user;

            if (!$user) continue;

            $activeDriver = $activeDrivers->get($user->id);

            // Kumpulkan SEMUA status unik (misal: OK, NG, UNKNOWN)
            $statusList = $reports->map(function ($r) {
                return [
                    'text' => $r->status,
                    'class' => strtolower($r->status) === 'ok' ? 'ok' : (strtolower($r->status) === 'ng' ? 'ng' : 'unknown')
                ];
            })->unique('text')->values();

            // Waktu terakhir aktivitas
            $lastTime = $firstReport->created_at;

            // Jumlah item yang diisi user hari ini (jumlah laporan)
            $itemFilled = $reports->count();

            $feedData[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'plat_nomor' => $user->plat_nomor ?? '-',
                'driver_name' => $activeDriver && $activeDriver->driver ? $activeDriver->driver->name : 'Belum Pilih Driver',
                'status_list' => $statusList->toArray(),
                'item_filled' => $itemFilled,
                'last_login' => $lastTime ? \Carbon\Carbon::parse($lastTime)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : null,
                'last_login_formatted' => $lastTime ? \Carbon\Carbon::parse($lastTime)->setTimezone('Asia/Jakarta')->format('H:i') : '-',
            ];
        }

        // 4. Handle User yang login tapi BELUM MELAPOR
        $usersNoReport = User::where('role', 'user')
            ->whereDate('last_login_at', $today)
            ->whereNotIn('id', $userIdsReported)
            ->get();

        $userIdsNoReport = $usersNoReport->pluck('id');
        $activeDriversNoReport = UserActiveDriver::whereIn('user_id', $userIdsNoReport)
            ->where('date', $today)
            ->with('driver')
            ->get()
            ->keyBy('user_id');

        foreach ($usersNoReport as $user) {
            $activeDriver = $activeDriversNoReport->get($user->id);

            $feedData[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'plat_nomor' => $user->plat_nomor ?? '-',
                'driver_name' => $activeDriver && $activeDriver->driver ? $activeDriver->driver->name : 'Belum Pilih Driver',
                'status_list' => [['text' => 'Belum Laporan', 'class' => 'belum-laporan']],
                'item_filled' => 0,
                'last_login' => $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : null,
                'last_login_formatted' => $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->setTimezone('Asia/Jakarta')->format('H:i') : '-',
            ];
        }

        // 5. Sort final array by time descending
        usort($feedData, function ($a, $b) {
            return strtotime($b['last_login']) - strtotime($a['last_login']);
        });

        return $feedData;
    }
    /**
     * API endpoint for real-time driver feed (AJAX polling)
     */
    public function getDriverFeed()
    {
        $driverFeed = $this->getDriverFeedData();

        return response()->json([
            'feed' => $driverFeed,
            'count' => count($driverFeed),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    public function monthlyReports(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $userId = $request->input('user_id');

        $query = DailyReport::query()
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2))
            ->with(['user', 'subject.car']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $reports = $query->orderBy('date', 'desc')->orderBy('user_id')->get();

        // cek ketika eksport data ke excel 
        if ($request->has('export')) {
            $fileName = 'Rekap_Bulanan_' . \Carbon\Carbon::parse($month . '-01')->format('F_Y') . '.xlsx';
            return Excel::download(new AdminMonthlyReportExport($reports, $month), $fileName);
        }

        $summary = $reports->groupBy('status')->map->count()->toArray();
        $total = $reports->count();

        // Prepare chart data - group by date and status
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

        $chartData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%02d', $year, $monthNum, $day);
            $chartData[$date] = ['OK' => 0, 'NG' => 0, 'UNKNOWN' => 0];
        }

        foreach ($reports as $report) {
            $date = $report->date->format('Y-m-d');
            if (isset($chartData[$date])) {
                $chartData[$date][$report->status]++;
            }
        }

        $dates = array_keys($chartData);
        $dayLabels = array_map(fn($d) => substr($d, 8), $dates);
        $okData = array_map(fn($d) => $d['OK'], $chartData);
        $ngData = array_map(fn($d) => $d['NG'], $chartData);
        $unknownData = array_map(fn($d) => $d['UNKNOWN'], $chartData);

        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.reports.monthly', compact('reports', 'month', 'summary', 'total', 'users', 'userId', 'dayLabels', 'okData', 'ngData', 'unknownData'));
    }

    public function dailyReportsChart(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $userId = $request->input('user_id');

        // dapatkan semua hari dalam bulan tersebut
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

        // dapatkan laporan harian untuk bulan dan user yang dipilih 
        $query = DailyReport::query()
            ->whereYear('date', '=', $year)
            ->whereMonth('date', '=', $monthNum)
            ->with(['user']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $reports = $query->get();

        // siapkan data chart 
        $chartData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%02d', $year, $monthNum, $day);
            $chartData[$date] = ['OK' => 0, 'NG' => 0, 'UNKNOWN' => 0];
        }

        foreach ($reports as $report) {
            $date = $report->date->format('Y-m-d');
            if (isset($chartData[$date])) {
                $chartData[$date][$report->status]++;
            }
        }

        $dates = array_keys($chartData);
        $okData = array_values(array_map(fn($d) => $d['OK'], $chartData));
        $ngData = array_values(array_map(fn($d) => $d['NG'], $chartData));
        $unknownData = array_values(array_map(fn($d) => $d['UNKNOWN'], $chartData));

        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.reports.chart', compact('dates', 'okData', 'ngData', 'unknownData', 'month', 'userId', 'users'));
    }

    public function summaryChart(Request $request)
    {
        // Filter by month if provided
        $month = $request->input('month', now()->format('Y-m'));
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        $allReports = DailyReport::whereYear('date', $year)
            ->whereMonth('date', $monthNum)
            ->get();

        $summary = $allReports->groupBy('status')->map->count();
        $totalReports = $allReports->count();

        return view('admin.reports.summary-chart', compact('summary', 'totalReports'));
    }
}
