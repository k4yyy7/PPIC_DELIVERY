<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserActiveDriver;
use App\Models\DriverItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ActiveDriverController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $drivers = \App\Models\Driver::orderBy('name')->get();
        $active = UserActiveDriver::where('user_id', Auth::id())
            ->where('date', $date)
            ->first();
        return view('user.driver.active', compact('drivers', 'active', 'date'));
    }

    public function store(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);
        UserActiveDriver::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'date' => $date,
            ],
            [
                'driver_id' => $request->driver_id,
                'driver_item_id' => null,
            ]
        );
        return redirect()->route('user.driver.active', ['date' => $date])
            ->with('success', 'Driver aktif hari ini berhasil dipilih.');
    }
}
