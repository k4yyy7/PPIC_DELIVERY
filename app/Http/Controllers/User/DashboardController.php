<?php
namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverItem;
use App\Models\ArmadaItem;
use App\Models\Dokument;
use App\Models\Environment;
use App\Models\Safety;
use App\Models\User;
use App\Models\Driver;
use App\Models\Car;
use App\Models\DailyReport;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $totalDriver = Driver::count();
        $totalArmada = Car::count();

        // Ambil semua item dari setiap kategori
        $driverItems = DriverItem::all();
        $armadaItems = ArmadaItem::all();
        $dokumentItems = Dokument::all();
        $environmentItems = Environment::all();
        $safetyItems = Safety::all();

        // Gabungkan semua item ke satu array
        $allItems = collect()
            ->merge($driverItems)
            ->merge($armadaItems)
            ->merge($dokumentItems)
            ->merge($environmentItems)
            ->merge($safetyItems);

        $totalItem = $allItems->count();

        $laporanHariIni = DailyReport::whereDate('date', $today)
            ->count();


        // Ambil semua DailyReport user hari ini
        $userReports = DailyReport::where('user_id', $userId)
            ->whereDate('date', $today)
            ->get();

        // Buat array status per item (OK/NG)
        $itemStatuses = [];
        $itemTerisi = 0;
        foreach ($allItems as $item) {
            $subjectType = get_class($item);
            $subjectId = $item->id;

            $report = $userReports
                ->where('subject_type', $subjectType)
                ->where('subject_id', $subjectId)
                ->whereIn('status', ['OK', 'NG'])
                ->first();

            $isFilled = $report ? true : false;

            $itemStatuses[] = [
                'label' => ($item->name ?? $item->nama ?? $item->judul ?? $item->kode ?? 'Item #'.$item->id),
                'type' => class_basename($subjectType),
                'isFilled' => $isFilled,
            ];

            if ($isFilled) $itemTerisi++;
        }

        // Progress per type
        $typeProgress = collect($itemStatuses)
            ->groupBy('type')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'filled' => $items->where('isFilled', true)->count(),
                ];
            });

        $persen = $totalItem > 0
            ? round(($itemTerisi / $totalItem) * 100)
            : 0;

        $progressColor = $persen == 100 ? 'success' : ($persen > 50 ? 'warning' : 'danger');

        // Hitung total OK (sudah) dan NG (belum) untuk seluruh item
        $totalOk = $itemTerisi;
        $totalNg = $totalItem - $itemTerisi;

        // Ambil driver aktif hari ini untuk user ini
        $activeDriver = null;
        $userActiveDriver = \App\Models\UserActiveDriver::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        if ($userActiveDriver && $userActiveDriver->driver_id) {
            $activeDriver = \App\Models\Driver::find($userActiveDriver->driver_id);
        }

        return view('user.dashboard-user', compact(
            'totalDriver',
            'totalArmada',
            'totalItem',
            'laporanHariIni',
            'itemTerisi',
            'persen',
            'progressColor',
            'itemStatuses',
            'totalOk',
            'totalNg',
            'typeProgress',
            'activeDriver'
        ));
    }
}