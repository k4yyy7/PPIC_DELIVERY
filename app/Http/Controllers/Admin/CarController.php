<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of cars.
     */
    public function index()
    {
        $cars = Car::orderBy('id', 'desc')->paginate(15);
        return view('admin.cars.indexcars', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        return view('admin.cars.createcars');
    }

    /**
     * Store a newly created car in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mobil' => 'required|string|max:255',
            'plat_nomor' => 'required|string|unique:cars,plat_nomor|max:255',
        ]);

        Car::create([
            'nama_mobil' => $validated['nama_mobil'],
            'plat_nomor' => $validated['plat_nomor'],
        ]);

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified car.
     */
    public function edit($id)
    {
        $car = Car::findOrFail($id);
        return view('admin.cars.editcars', compact('car'));
    }

    /**
     * Update the specified car in storage.
     */
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validate([
            'nama_mobil' => 'required|string|max:255',
            'plat_nomor' => 'required|string|unique:cars,plat_nomor,' . $id . '|max:255',
        ]);

        $car->nama_mobil = $validated['nama_mobil'];
        $car->plat_nomor = $validated['plat_nomor'];
        $car->save();

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil diperbarui');
    }

    /**
     * Remove the specified car from storage.
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Mobil berhasil dihapus');
    }
}
