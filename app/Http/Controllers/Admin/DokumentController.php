<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumentController extends Controller
{
    public function index()
    {
        $dokuments = Dokument::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dokument.indexdokument', compact('dokuments'));
    }

    public function create()
    {
        return view('admin.dokument.createdokument');
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

        Dokument::create($data);

        return redirect()->route('dokument.index')->with('success', 'Dokument berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dokument = Dokument::findOrFail($id);

        return view('admin.dokument.editdokument', compact('dokument'));
    }

    public function update(Request $request, $id)
    {
        $dokument = Dokument::findOrFail($id);

        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::id();

        $dokument->update($data);

        return redirect()->route('dokument.index')->with('success', 'Dokument berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokument = Dokument::findOrFail($id);
        $dokument->delete();

        return redirect()->route('dokument.index')->with('success', 'Dokument berhasil dihapus.');
    }
}
