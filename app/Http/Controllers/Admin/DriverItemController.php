<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverItem;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverItemController extends Controller
{
    public function index()
    {
        $driverItems = DriverItem::with('car')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.driveritem.indexdriveritem', compact('driverItems'));
    }

    public function create()
    {
        return view('admin.driveritem.createdriveritem');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);


        $data['status'] = '?';
        $data['updated_by'] = Auth::id();
        $data['cars_id'] = null;

        DriverItem::create($data);

        return redirect()->route('driver-items.index')->with('success', 'Driver item berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $driverItem = DriverItem::findOrFail($id);

        return view('admin.driveritem.editdriveritem', compact('driverItem'));
    }

    public function update(Request $request, $id)
    {
        $driverItem = DriverItem::findOrFail($id);


        $data = $request->validate([
            'safety_items' => 'nullable|string',
            'standard_items' => 'nullable|string',
        ]);

        $data['updated_by'] = Auth::id();

        $driverItem->update($data);

        return redirect()->route('driver-items.index')->with('success', 'Driver item berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $driverItem = DriverItem::findOrFail($id);
        if ($driverItem->image_evidence && Storage::disk('public')->exists($driverItem->image_evidence)) {
            Storage::disk('public')->delete($driverItem->image_evidence);
        }

        $driverItem->delete();

        return redirect()->route('driver-items.index')->with('success', 'Driver item berhasil dihapus.');
    }
}
