<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Order;
use App\Models\OrderAddon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class AddonsController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('created_at','desc')->get();

        return view('dashboard.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('dashboard.addons.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable'
        ]);

        Addon::create($validatedData);

        return response()->json();
    }

    public function print($id)
    {
        $order = OrderAddon::with(['order' , 'addon'])->find($id);

        $html = view('dashboard.addons.print', compact('order'))->render();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('invoice.pdf', 'I');

    }

    public function show( $addon)
    {
        $addon = Addon::with('orders')->findOrFail($addon);
        $orders = $addon->orders()->paginate(100);


        return view('dashboard.addons.show', compact('addon','orders'));
    }

    public function edit( $addon)
    {
        $addon = Addon::findOrFail($addon);
        return view('dashboard.addons.create', compact('addon'));
    }

    public function update(Request $request,  $addon)
    {
        $addon = Addon::findOrFail($addon);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);


        $addon->update($validatedData);
        return response()->json();
    }

    public function destroy( $addon)
    {
        $addon = Addon::findOrFail($addon);
        $addon->delete();
        return response()->json();
    }


    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if (Addon::whereIn('id', $ids)->delete()) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }
}
