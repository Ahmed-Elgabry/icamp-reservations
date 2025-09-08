<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TermsSittng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderSignatureController extends Controller
{
    public function show(Order $order, Request $request)
    {
        // Handle language switching
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['ar', 'en'])) {
                app()->setLocale($locale);
                session(['locale' => $locale]);
            }
        } else if (session('locale')) {
            app()->setLocale(session('locale'));
        }
        
        if ($order->signature_path) {
            return view('signature.already', compact('order'));
        }
        return view('signature.signature', [
            'order' => $order,
            'terms' => TermsSittng::first()
        ]);
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
            'signature_path'=> $path,
            'signature' => now()
        ]);

        return redirect()->back()->with('success' , __('dashboard.success'));
    }

    public function destroy(Order $order)
    {
        // Delete signature image from storage if present
        if ($order->signature_path && Storage::disk('public')->exists($order->signature_path)) {
            Storage::disk('public')->delete($order->signature_path);
        }

        // Clear signature fields on the order
        $order->update([
            'signature_path' => null,
            'signature' => null,
        ]);

        // If the request is AJAX/JSON (used by sending-forms.js), return JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => __('dashboard.success')]);
        }

        // Fallback to redirect for normal requests
        return redirect()->back()->with('success', __('dashboard.success'));
    }
    
}
