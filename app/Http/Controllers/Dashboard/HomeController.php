<?php

namespace App\Http\Controllers\Dashboard;
use DB;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Customer;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $totalCustomersCount = Customer::count();
        $totalOrdersCount = Order::count();
        $ordersCountByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $latestOrders = Order::latest()->take(20)->get(); // Fetch latest 15 orders
        $latestPayments = Payment::with('order')->latest()->take(15)->get(); // Fetch latest 15 payments

        return view('home', compact('totalCustomersCount', 'totalOrdersCount', 'ordersCountByStatus', 'latestOrders', 'latestPayments'));
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
        // الحصول على أكثر الخدمات مبيعًا
        $topServices = Service::whereHas('orders', function($query) {
            $query->where('status', 'completed');
        })
        ->with(['orders' => function($query) {
            $query->where('status', 'completed');
        }])
        ->withCount(['orders' => function($query) {
            $query->where('status', 'completed');
        }])
        ->orderBy('orders_count', 'desc')
        ->take(10)
        ->get();
    
        // الحصول على الحسابات البنكية
        $bankAccounts = BankAccount::all();


        // الحصول على الأرباح
        $totalPayments = Payment::where('verified', '1')->sum('price');

        // الحصول على المصاريف وبنودها
        $expenses = Expense::select('expense_item_id', DB::raw('SUM(price) as total'))
            ->groupBy('expense_item_id')
            ->with('expenseItem')
            ->get();

        // بيانات إضافية يمكن أن تكون مطلوبة
        $monthlyPayments = Payment::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(price) as total'))
            ->groupBy('month')
            ->get();

        return view('dashboard.reports', compact('topServices', 'totalPayments','bankAccounts', 'expenses', 'monthlyPayments'));
    }


}
