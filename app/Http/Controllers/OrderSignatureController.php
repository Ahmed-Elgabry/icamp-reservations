<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderSignatureController extends Controller
{
    public function show(Order $order)
    {
        if ($order->signature_path) {
            return view('signature.already', compact('order'));
        }
        return view('signature.signature', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'signature' => ['required','string'],
        ]);

        $dataUrl = $request->input('signature');
        if (!preg_match('#^data:image/(png|jpe?g);base64,#i', $dataUrl)) {
            return back()->withErrors(['signature' => 'Invalid signature image.']);
        }

        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataUrl));
        $path  = 'signatures/'.$order->id.'-'.Str::random(8).'.png';

        Storage::disk('public')->put($path, $image);

        $order->update([
            'signature_path'      => $path,
            'signature' => now()
        ]);

        return redirect()->back()->with('success' , __('dashboard.success'));
    }
}
