<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\User;
use App\Models\Car;
use Carbon\Carbon;

class LandingController extends Controller
{
    // ── Helper: ambil plat nomor dari report ──────────────
    private function getPlat($report)
    {
        if ($report->subject && $report->subject->car && $report->subject->car->plat_nomor)
            return $report->subject->car->plat_nomor;
        if ($report->user && $report->user->plat_nomor)
            return $report->user->plat_nomor;
        return null;
    }

    // ── Helper: build aggregat per armada ────────────────
    private function buildPlatAggregates($reports)
    {
        $data = [];
        foreach ($reports as $report) {
            $plat = $this->getPlat($report);
            if (!$plat) continue;
            $status = strtoupper($report->status ?? 'UNKNOWN');
            $driver = $report->driver_name ?? ($report->user->name ?? 'Unknown');
            if (!isset($data[$plat])) {
                $data[$plat] = ['ok' => 0, 'ng' => 0, 'unknown' => 0, 'driver_name' => $driver];
            }
            if ($status === 'OK')      $data[$plat]['ok']++;
            elseif ($status === 'NG')  $data[$plat]['ng']++;
            else                       $data[$plat]['unknown']++;
        }
        return $data;
    }

    /**
     * ── Helper: build trend 7 hari (dedupe user per hari, total item) ──────
     */
    private function buildTrend7Days(int $offset = 0): array
    {
        $endDate   = Carbon::today()->subDays($offset * 7);
        $startDate = $endDate->copy()->subDays(6);

        $allReports = DailyReport::with(['user'])
            ->whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->orderBy('id', 'asc')
            ->get();

        $byDateItem = [];
        foreach ($allReports as $report) {
            $dateKey = Carbon::parse($report->date)->format('Y-m-d');
            $uniqKey = $report->user_id . '|' . $report->subject_type . '|' . $report->subject_id;
            $byDateItem[$dateKey][$uniqKey] = $report;
        }

        $labels  = [];
        $okData  = [];
        $ngData  = [];
        $unkData = [];

        for ($i = 0; $i < 7; $i++) {
            $d       = $startDate->copy()->addDays($i);
            $key     = $d->format('Y-m-d');
            $labels[] = $d->format('d M');

            $itemReports = $byDateItem[$key] ?? [];

            $ok  = 0;
            $ng  = 0;
            $unk = 0;

            foreach ($itemReports as $report) {
                $status = strtoupper($report->status ?? '');
                if ($status === 'OK')     $ok++;
                elseif ($status === 'NG') $ng++;
                else                      $unk++;
            }

            $okData[]  = $ok;
            $ngData[]  = $ng;
            $unkData[] = $unk;
        }

        $weekLabel = match($offset) {
            0 => 'Minggu Ini',
            1 => 'Minggu Lalu',
            default => "{$offset} Minggu Lalu"
        };

        return [$labels, $okData, $ngData, $unkData, $weekLabel];
    }

    // ── Helper: hitung total item seluruh kategori ───────
    private function getTotalItem(): int
    {
        return \App\Models\DriverItem::count()
             + \App\Models\ArmadaItem::count()
             + \App\Models\Dokument::count()
             + \App\Models\Environment::count()
             + \App\Models\Safety::count();
    }

    // ── INDEX — halaman landing ───────────────────────────
    public function index()
    {
        $today        = Carbon::today();
        $reportsToday = DailyReport::with(['subject.car', 'user'])
            ->whereDate('date', $today)
            ->orderBy('id', 'asc')
            ->get();

        [$lineLabels, $okData, $ngData, $unknownData, $weekLabel] = $this->buildTrend7Days(0);

        // Hitung total OK/NG/? hari ini (dedupe per user+item)
        $allReportsToday = DailyReport::with(['user'])
            ->whereDate('date', $today)
            ->orderBy('id', 'asc')
            ->get();
        $byItemToday = [];
        foreach ($allReportsToday as $report) {
            $uniqKey = $report->user_id . '|' . $report->subject_type . '|' . $report->subject_id;
            $byItemToday[$uniqKey] = $report;
        }
        $todayOk = 0;
        $todayNg = 0;
        $todayUnknown = 0;
        foreach ($byItemToday as $report) {
            $status = strtoupper($report->status ?? '');
            if ($status === 'OK') $todayOk++;
            elseif ($status === 'NG') $todayNg++;
            else $todayUnknown++;
        }

        $totalDriver   = \App\Models\Driver::count();
        $totalArmada   = Car::count();
        $totalItem     = $this->getTotalItem();
        $todayReports  = $reportsToday->count();
        $userIsiHariIni = $todayReports;

        $platAggregates   = $this->buildPlatAggregates($reportsToday);
        $armadaLoginCount = count($platAggregates);

        return view('landing', compact(
            'platAggregates',
            'lineLabels', 'okData', 'ngData', 'unknownData', 'weekLabel',
            'totalDriver', 'totalArmada', 'totalItem', 'todayReports',
            'armadaLoginCount', 'userIsiHariIni',
            'todayOk', 'todayNg', 'todayUnknown'
        ));
    }

    // ── LIVE DATA — endpoint JSON ─────────────────────────
    public function liveData(Request $request)
    {
        $offset       = max(0, (int) $request->get('offset', 0));
        $today        = Carbon::today();
        $reportsToday = DailyReport::with(['subject.car', 'user'])
            ->whereDate('date', $today)
            ->orderBy('id', 'asc')
            ->get();

        [$lineLabels, $okData, $ngData, $unknownData, $weekLabel] = $this->buildTrend7Days($offset);

        $platAggregates   = $this->buildPlatAggregates($reportsToday);
        $todayReports     = $reportsToday->count();
        $armadaLoginCount = count($platAggregates);
        $userIsiHariIni   = $todayReports;

        // Dedupe per user+item untuk stat hari ini
        $byItemToday = [];
        foreach ($reportsToday as $report) {
            $uniqKey = $report->user_id . '|' . $report->subject_type . '|' . $report->subject_id;
            $byItemToday[$uniqKey] = $report;
        }
        $todayOk = 0;
        $todayNg = 0;
        $todayUnknown = 0;
        foreach ($byItemToday as $report) {
            $status = strtoupper($report->status ?? '');
            if ($status === 'OK') $todayOk++;
            elseif ($status === 'NG') $todayNg++;
            else $todayUnknown++;
        }

        $okCount  = 0;
        $ngCount  = 0;
        $unkCount = 0;
        foreach ($platAggregates as $data) {
            if ($data['ok'] > 0)      $okCount++;
            elseif ($data['ng'] > 0)  $ngCount++;
            else                       $unkCount++;
        }

        return response()->json([
            'platAggregates'   => $platAggregates,
            'lineLabels'       => $lineLabels,
            'okData'           => $okData,
            'ngData'           => $ngData,
            'unknownData'      => $unknownData,
            'weekLabel'        => $weekLabel,
            'todayReports'     => $todayReports,
            'armadaLoginCount' => $armadaLoginCount,
            'userIsiHariIni'   => $userIsiHariIni,
            'okCount'          => $okCount,
            'ngCount'          => $ngCount,
            'unkCount'         => $unkCount,
            'todayOk'          => $todayOk,
            'todayNg'          => $todayNg,
            'todayUnknown'     => $todayUnknown,
            // ── Tambahan untuk real-time update stat cards ──
            'totalDriver'      => \App\Models\Driver::count(),
            'totalItem'        => $this->getTotalItem(),
        ]);
    }
}
