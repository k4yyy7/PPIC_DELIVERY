<?php

namespace App\Http\Controllers\User;

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
        // Mengambil jumlah driver dari tabel Driver
        $totalDriver = Driver::count();
        // Mengambil jumlah armada/mobil
        $totalArmada = Car::count();
        // Mengambil total item (Driver Item + Armada Item + Dokument + Environment + Safety)
        $totalItem = DriverItem::count() + ArmadaItem::count() + Dokument::count() + Environment::count() + Safety::count();
        // Mengambil laporan hari ini
        $laporanHariIni = DailyReport::whereDate('date', now()->toDateString())
            ->count();

        return view('user.dashboard-user', compact('totalDriver', 'totalArmada', 'totalItem', 'laporanHariIni'));
    }
}
