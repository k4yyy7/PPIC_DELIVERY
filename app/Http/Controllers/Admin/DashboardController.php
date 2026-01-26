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
use App\Exports\AdminMonthlyReportExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
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
        
        return view('admin.dashboard-admin', compact('totalDriver', 'totalArmada', 'totalItem', 'laporanHariIni', 'chartData'));
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
            $fileName = 'Rekap_Bulanan_'.\Carbon\Carbon::parse($month.'-01')->format('F_Y').'.xlsx';
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
        // Overall chart - semua laporean dalam sistem
        $allReports = DailyReport::all();
        
        $summary = $allReports->groupBy('status')->map->count();
        $totalReports = $allReports->count();

        return view('admin.reports.summary-chart', compact('summary', 'totalReports'));
    }
}
