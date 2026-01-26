<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\DriverItem;
use App\Models\ArmadaItem;
use App\Models\Dokument;
use App\Models\Environment;
use App\Models\Safety;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Exports\UserHistoryReportExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $driverItems = DriverItem::orderBy('id')->get();
        $armadaItems = ArmadaItem::orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.index', compact('date', 'driverItems', 'armadaItems', 'reports'));
    }

    public function driverIndex(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $driverItems = DriverItem::with('car')->orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->where('subject_type', DriverItem::class)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.driver', compact('date', 'driverItems', 'reports'));
    }

    public function armadaIndex(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $armadaItems = ArmadaItem::with('car')->orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->where('subject_type', ArmadaItem::class)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.armada', compact('date', 'armadaItems', 'reports'));
    }

    public function dokumenIndex(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $dokuments = Dokument::orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->where('subject_type', Dokument::class)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.dokument', compact('date', 'dokuments', 'reports'));
    }

    public function environmentIndex(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $environments = Environment::orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->where('subject_type', Environment::class)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.environment', compact('date', 'environments', 'reports'));
    }

    public function safetyIndex(Request $request)
    {
        $date = $request->input('date');
        $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();

        $safetys = Safety::orderBy('id')->get();

        // Laporan milik user yang digunakan untuk mengisi otomatis form
        $reports = DailyReport::where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->where('subject_type', Safety::class)
            ->get()
            ->keyBy(function ($r) {
                return $r->subject_type.'_'.$r->subject_id;
            });

        return view('user.daily.safety', compact('date', 'safetys', 'reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'required|in:driver,armada,dokument,environment,safety',
            'subject_id' => 'required|integer',
            'status' => 'required|in:OK,NG,UNKNOWN',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
            'date' => 'nullable|date',
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        // Mapping tipe subject ke class model (hindari nested ternary)
        $subjectClass = match ($validated['subject_type']) {
            'driver' => \App\Models\DriverItem::class,
            'armada' => \App\Models\ArmadaItem::class,
            'dokument' => \App\Models\Dokument::class,
            'environment' => \App\Models\Environment::class,
            default => \App\Models\Safety::class,
        };

        // Simpan gambar yang sudah ada jika pengguna tidak mengunggah yang baru
        $existingImage = DailyReport::where('date', $date)
            ->where('user_id', Auth::id())
            ->where('subject_type', $subjectClass)
            ->where('subject_id', $validated['subject_id'])
            ->value('image_path');

        $imagePath = $existingImage;
        if ($request->hasFile('image')) {
            $dir = 'daily_reports/'.date('Y/m').'/'.Auth::id();
            $filename = $validated['subject_type'].'_'.$validated['subject_id'].'_'.$date.'.'.$request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs($dir, $filename, 'public');
        }

        // Ambil nama driver aktif hari ini untuk semua tipe laporan
        $driverName = null;
        $activeDriver = \App\Models\UserActiveDriver::where('user_id', Auth::id())
            ->where('date', $date)
            ->with('driver')
            ->first();
        $driverName = $activeDriver && $activeDriver->driver ? $activeDriver->driver->name : null;
        DailyReport::updateOrCreate(
            [
                'date' => $date,
                'user_id' => Auth::id(),
                'subject_type' => $subjectClass,
                'subject_id' => $validated['subject_id'],
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'image_path' => $imagePath,
                'driver_name' => $driverName,
            ]
        );

        $anchorId = $validated['subject_type'].'-'.$validated['subject_id'];

        return redirect()
            ->back()
            ->with('success', 'Laporan harian tersimpan.')
            ->with('success_target', $anchorId)
            ->withFragment('report-' . $anchorId);
    }

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'required|in:driver,armada,dokument,environment,safety',
            'date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.subject_id' => 'required|integer',
            'items.*.status' => 'required|in:OK,NG,UNKNOWN',
            'items.*.notes' => 'nullable|string',
            'items.*.image' => 'nullable|image|max:8192',
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        $subjectClass = match ($validated['subject_type']) {
            'driver' => \App\Models\DriverItem::class,
            'armada' => \App\Models\ArmadaItem::class,
            'dokument' => \App\Models\Dokument::class,
            'environment' => \App\Models\Environment::class,
            default => \App\Models\Safety::class,
        };

        foreach ($validated['items'] as $index => $item) {
            // Tetap gunakan gambar yang sudah ada jika pengguna tidak mengunggah yang baru
            $existingImage = DailyReport::where('date', $date)
                ->where('user_id', Auth::id())
                ->where('subject_type', $subjectClass)
                ->where('subject_id', $item['subject_id'])
                ->value('image_path');

            $imagePath = $existingImage;
            $imageFile = $request->file("items.$index.image");
            if ($imageFile) {
                $dir = 'daily_reports/' . date('Y/m') . '/' . Auth::id();
                $filename = $validated['subject_type'] . '_' . $item['subject_id'] . '_' . $date . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = $imageFile->storeAs($dir, $filename, 'public');
            }

            // Isi nama driver aktif hari ini untuk semua tipe laporan
            $driverName = null;
            $activeDriver = \App\Models\UserActiveDriver::where('user_id', Auth::id())
                ->where('date', $date)
                ->with('driver')
                ->first();
            $driverName = $activeDriver && $activeDriver->driver ? $activeDriver->driver->name : null;
            DailyReport::updateOrCreate(
                [
                    'date' => $date,
                    'user_id' => Auth::id(),
                    'subject_type' => $subjectClass,
                    'subject_id' => $item['subject_id'],
                ],
                [
                    'status' => $item['status'],
                    'notes' => $item['notes'] ?? null,
                    'image_path' => $imagePath,
                    'driver_name' => $driverName,
                ]
            );
        }

        return redirect()->back()->with('success', 'Semua laporan tersimpan.');
    }

    public function history(Request $request)
    {
        $query = DailyReport::where('user_id', Auth::id())
            ->with(['subject'])
            ->orderBy('date', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Cek apakah pengguna meminta ekspor
        if ($request->has('export')) {
            $reports = $query->get();
            $fileName = 'Riwayat_Laporan_'.now()->format('Y_m_d').'.xlsx';
            return Excel::download(new UserHistoryReportExport($reports), $fileName);
        }

        // Pertahankan parameter filter saat pengguna pindah halaman
        $reports = $query->paginate(20)->withQueryString();

        return view('user.daily.history', compact('reports'));
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $reports = DailyReport::where('user_id', Auth::id())
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2))
            ->with(['subject'])
            ->orderBy('date', 'desc')
            ->get();

        $summary = $reports->groupBy('status')->map->count()->toArray();
        $total = $reports->count();

        return view('user.daily.monthly', compact('reports', 'month', 'summary', 'total'));
    }
}
