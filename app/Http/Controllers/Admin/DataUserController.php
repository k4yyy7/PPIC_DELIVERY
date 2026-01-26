<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{
    /**
     * Display a listing of data users.
     */
    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(15);
        return view('admin.datauser.indexdatauser', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $cars = Car::orderBy('nama_mobil')->get();
        return view('admin.datauser.createdatauser', compact('cars'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
            'plat_nomor' => 'nullable|string|exists:cars,plat_nomor|unique:users,plat_nomor',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'plat_nomor' => $validated['plat_nomor'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('datauser.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $cars = Car::orderBy('nama_mobil')->get();
        return view('admin.datauser.editdatauser', compact('user', 'cars'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:5|confirmed',
            'plat_nomor' => 'nullable|string|exists:cars,plat_nomor|unique:users,plat_nomor,' . $id,
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->plat_nomor = $validated['plat_nomor'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('datauser.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('datauser.index')->with('success', 'User berhasil dihapus');
    }
}
