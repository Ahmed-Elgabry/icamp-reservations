<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

class statisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $createdBy = $request->input('created_by');
        $servicesFilter = $request->input('services');  // تأكد من أن هذا يحتوي على مصفوفة من ID الخدمات
        $services = Service::all();  // يمكنك أن تبقي هذه المتغير للاستخدام في العرض
        $users = User::all();
        $ordersQuery = Order::query();
    
        if ($fromDate) {
            $ordersQuery->where('created_at', '>=', $fromDate);
        }
    
        if ($toDate) {
            $ordersQuery->where('created_at', '<=', $toDate);
        }
    
        if ($createdBy) {
            $ordersQuery->where('created_by', $createdBy);
        }
    
        if ($servicesFilter) {
            // فلترة الطلبات بناءً على الخدمات
            $ordersQuery->whereHas('services', function ($query) use ($servicesFilter) {
                $query->whereIn('services.id', (array) $servicesFilter);  // هنا يتم استخدام ID الخدمات
            });
        }
    
        $orders = $ordersQuery->count();
        $paidOrders = $ordersQuery->where('status', 'pending')->count();
        $approvedOrders = $ordersQuery->where('status', 'approved')->count();
        $payments = Payment::all();
    
        $topServices = Service::whereHas('orders', function ($query) use ($fromDate, $toDate, $createdBy, $servicesFilter) {
            $query->where('orders.status', 'completed');
    
            if ($fromDate) {
                $query->where('orders.created_at', '>=', $fromDate);
            }
    
            if ($toDate) {
                $query->where('orders.created_at', '<=', $toDate);
            }
    
            if ($createdBy) {
                $query->where('orders.created_by', $createdBy);
            }
    
            if ($servicesFilter) {
                $query->whereHas('services', function ($query) use ($servicesFilter) {
                    $query->whereIn('services.id', (array) $servicesFilter); 
                });
            }
        })
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();
    
        return view('dashboard.statistics', compact('orders', 'users', 'paidOrders', 'approvedOrders', 'payments', 'topServices', 'fromDate', 'toDate', 'createdBy', 'servicesFilter', 'services'));
    }    

}