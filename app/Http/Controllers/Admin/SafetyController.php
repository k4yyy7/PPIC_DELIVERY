<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Safety;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SafetyController extends Controller
{
    public function index()
    {
        $safetys = Safety::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.safety.indexsafety', compact('safetys'));
    }

    public function create()
    {
        return view('admin.safety.createsafety');
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

        Safety::create($data);

        return redirect()->route('safety.index')->with('success', 'Safety berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $safety = Safety::findOrFail($id);

        return view('admin.safety.editsafety', compact('safety'));
    }

    public function update(Request $request, $id)
    {
        $safety = Safety::findOrFail($id);

        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::id();

        $safety->update($data);

        return redirect()->route('safety.index')->with('success', 'Safety berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $safety = Safety::findOrFail($id);
        $safety->delete();

        return redirect()->route('safety.index')->with('success', 'Safety berhasil dihapus.');
    }
}
