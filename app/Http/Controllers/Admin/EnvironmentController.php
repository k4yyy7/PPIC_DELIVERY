<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    public function index()
    {
        $environments = Environment::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.environment.indexenvironment', compact('environments'));
    }

    public function create()
    {
        return view('admin.environment.createenvironment');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['status'] = 'UNKNOWN';
        $data['updated_by'] = Auth::id();
        $data['cars_id'] = null;

        Environment::create($data);

        return redirect()->route('environment.index')->with('success', 'Environment berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $environment = Environment::findOrFail($id);

        return view('admin.environment.editenvironment', compact('environment'));
    }

    public function update(Request $request, $id)
    {
        $environment = Environment::findOrFail($id);

        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::id();

        $environment->update($data);

        return redirect()->route('environment.index')->with('success', 'Environment berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $environment = Environment::findOrFail($id);
        $environment->delete();

        return redirect()->route('environment.index')->with('success', 'Environment berhasil dihapus.');
    }
}
