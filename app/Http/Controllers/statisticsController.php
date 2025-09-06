<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\Models\GeneralPayment;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

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
        $servicesFilter = $request->input('services');  // تأكد من أن هذا يحتوي على مصفوفة من ID نوع المخيم
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
            // فلترة الطلبات بناءً على نوع المخيم
            $ordersQuery->whereHas('services', function ($query) use ($servicesFilter) {
                $query->whereIn('services.id', (array) $servicesFilter);  // هنا يتم استخدام ID نوع المخيم
            });
        }

    $orders = $ordersQuery->count();

    // Establish a continuous date range (defaults to last 30 days)
    // Default window: last 7 days inclusive of today
    $startDate = $fromDate ? Carbon::parse($fromDate)->toDateString() : Carbon::today()->subDays(6)->toDateString();
    $endDate = $toDate ? Carbon::parse($toDate)->toDateString() : Carbon::today()->toDateString();
             $ordersCountByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
  
        // General revenues sum by day (insurances not returned, addons, warehouse sales)
        $general_revenues_by_day = Transaction::where(function($query) {
                $query->where(function($q){
                        $q->where('source', 'insurances')
                          ->whereHas('payment', function($qq){
                              $qq->whereNot('insurance_status', 'returned');
                          });
                    })
                    ->orWhereIn('source', ['reservation_addon', 'warehouse_sale']);
            })
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Reservation revenues sum by day
        $reservation_revenues_by_day = Transaction::where('source', 'reservation_payments')
            ->where('verified', '1')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Combined totals by day: ensure continuous date series with zeros
        $generalMap = $general_revenues_by_day->keyBy('day');
        $reservationMap = $reservation_revenues_by_day->keyBy('day');
        $dates = [];
        for ($date = Carbon::parse($startDate); $date->lte(Carbon::parse($endDate)); $date->addDay()) {
            $dates[] = $date->toDateString();
        }
        $revenues_by_day = collect($dates)->map(function($day) use ($generalMap, $reservationMap){
            $g = (float) optional($generalMap->get($day))->total ?? 0;
            $r = (float) optional($reservationMap->get($day))->total ?? 0;
            return [
                'day' => $day,
                'general_total' => $g,
                'reservation_total' => $r,
                'total' => $g + $r,
            ];
        });

        // Grand totals (optional, for summary widgets)
        $general_revenues = (float) $general_revenues_by_day->sum('total');
        $reservation_revenues = (float) $reservation_revenues_by_day->sum('total');
    
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

        return view('dashboard.statistics', compact(
            'orders',
            'users',
            'ordersCountByStatus',
            'topServices',
            'general_revenues',
            'reservation_revenues',
            'general_revenues_by_day',
            'reservation_revenues_by_day',
            'revenues_by_day',
            'startDate',
            'endDate',
            'fromDate',
            'toDate',
            'createdBy',
            'servicesFilter',
            'services'
        ));
    }
}
