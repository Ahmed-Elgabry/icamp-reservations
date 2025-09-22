<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderStatusController extends Controller
{
    public function getOrderStatuses(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer|exists:orders,id'
        ]);

        $orders = Order::with(['payments' => function($query) {
            $query->where('verified', true);
        }])
        ->whereIn('id', $request->order_ids)
        ->get()
        ->map(function($order) {
            $totalPaid = $order->totalPaidAmount();
            $totalPrice = $order->price + $order->insurance_amount;
            
            $paymentStatus = 'unpaid';
            if ($totalPaid >= $totalPrice) {
                $paymentStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $paymentStatus = 'partial';
            }

            return [
                'id' => $order->id,
                'payment_status' => $paymentStatus,
                'paid_amount' => (float) $totalPaid,
                'total_price' => (float) $totalPrice,
                'signin_at' => $order->time_of_receipt,
                'logout_at' => $order->delivery_time,
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }
}
