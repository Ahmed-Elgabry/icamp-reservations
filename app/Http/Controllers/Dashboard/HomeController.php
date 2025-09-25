<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentSummaryService;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\GeneralPayment;
use App\Models\Expense;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $paymentSummaryService;

    /**
     * Create a new controller instance.
     *
     * @param PaymentSummaryService $paymentSummaryService
     * @return void
     */
    public function __construct(PaymentSummaryService $paymentSummaryService)
    {
        $this->middleware('auth');
        $this->paymentSummaryService = $paymentSummaryService;
    }

    public function index()
    {
        // Get dashboard data from the service
        $dashboardData = $this->paymentSummaryService->getDashboardData();

        // Get additional stats
        $totalCustomersCount = Customer::count();
        $totalOrdersCount = Order::count();

        // Get orders count by status
        $ordersCountByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Get upcoming reservations
        $upcomingReservations = Order::whereDate('date', '>=', now()->addDay()->startOfDay())
            ->with('customer')
            ->latest('date')
            ->take(20)
            ->get();

        return view('home', array_merge($dashboardData, [
            'totalCustomersCount' => $totalCustomersCount,
            'totalOrdersCount' => $totalOrdersCount,
            'ordersCountByStatus' => $ordersCountByStatus,
            'upcomingReservations' => $upcomingReservations
        ]));
    }

    public function calender()
    {
        $orders = Order::with('customer', 'services')->get();


        return view('dashboard.calender', [
            'orders' => $orders
        ]);
    }

    public function reprots()
    {


        $topServices = Service::whereHas('orders', function ($query) {
            $query->where('status', 'completed');
        })
            ->with(['orders' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withCount(['orders' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        // الحصول على الحسابات البنكية
        $bankAccounts = BankAccount::all();


        // الحصول على الأرباح
        $totalPayments = Payment::where('verified', '1')->sum('price');
        // الحصول على المدفوعات العامة من صفحة إضافة الأموال
        $payments = GeneralPayment::with(['account', 'order.customer', 'transaction'])
            ->whereHas('transaction', function($query) {
                $query->where('source', 'add_funds_page');
            })
            ->where('verified', true) // Also include by statement type
            ->orderBy('created_at', 'desc')
            ->get();
        // Latest up to 7 reservation payment transactions
        $reservations_revenues = \App\Models\Payment::whereNot('statement', 'the_insurance')
            ->where('verified', '1')
            ->orderBy('id', 'desc')
            ->limit(7)
            ->get(['id','price','created_at'])
            ->reverse() // oldest first for chart
            ->values();

        // الحصول على المصاريف وبنودها
        $expenses = Expense::select('expense_item_id', DB::raw('SUM(price) as total'))
            ->groupBy('expense_item_id')
            ->with('expenseItem')
            ->get();

        // بيانات إضافية يمكن أن تكون مطلوبة
        $monthlyPayments = Payment::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(price) as total'))
            ->groupBy('month')
            ->get();

    return view('dashboard.reports', compact('topServices', 'payments', 'totalPayments', 'bankAccounts', 'expenses', 'monthlyPayments', 'reservations_revenues'));
    }
}
