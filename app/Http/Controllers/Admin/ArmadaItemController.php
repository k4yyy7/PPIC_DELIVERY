<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArmadaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArmadaItemController extends Controller
{
    public function index()
    {
        $armadaItems = ArmadaItem::with('car')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.armadaitem.indexarmadaitem', compact('armadaItems'));
    }

    public function create()
    {
        return view('admin.armadaitem.createarmadaitem');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['status'] = '?';
        $data['updated_by'] = Auth::id();
        // id user yg sedang login
        $data['cars_id'] = null;

        ArmadaItem::create($data);

        return redirect()->route('armada-items.index')->with('success', 'Armada item berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $armadaItem = ArmadaItem::findOrFail($id);
        // memunculkan data yg akan diedit

        return view('admin.armadaitem.editarmadaitem', compact('armadaItem'));
    }

    public function update(Request $request, $id)
    {
        $armadaItem = ArmadaItem::findOrFail($id);

        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::id();

        $armadaItem->update($data);

        return redirect()->route('armada-items.index')->with('success', 'Armada item berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $armadaItem = ArmadaItem::findOrFail($id);
        if ($armadaItem->image_evidence && Storage::disk('public')->exists($armadaItem->image_evidence)) {
            Storage::disk('public')->delete($armadaItem->image_evidence);
        }

        $armadaItem->delete();

        return redirect()->route('armada-items.index')->with('success', 'Armada item berhasil dihapus.');
    }
}
