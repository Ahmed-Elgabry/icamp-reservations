<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Notice;
use App\Models\NoticeType;
use App\Models\Order;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::with(['customer', 'order', 'creator', 'type'])
            ->latest();

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $notices = $query->paginate(100);
        $customers = Customer::select('id', 'name')->get();
        $noticeTypes = NoticeType::where('is_active', true)->get();

        return view('dashboard.notices.index', compact('notices', 'customers', 'noticeTypes'));
    }

    public function getCustomerOrders($customer_id)
    {
        $orders = Order::where('customer_id', $customer_id)->select('id', 'customer_id')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'notice' => 'required|string|max:1000',
            'notice_type_id' => 'nullable|exists:notice_types,id',
        ]);

        Notice::create($validated + ['created_by' => auth()->id()]);
        return redirect()->route('notices.index')->with('success',  __('dashboard.notice_created_successfully'));
    }

    public function show(Notice $notice)
    {
        return view('dashboard.notices.show_content', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        $orders = Order::where('customer_id', $notice->customer_id)
            ->select('id', 'customer_id')
            ->get();

        return response()->json([
            'notice' => [
                'customer_id' => $notice->customer_id,
                'order_id' => $notice->order_id,
                'notice' => $notice->notice,
                'notice_type_id' => $notice->notice_type_id
            ],
            'orders' => $orders
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'notice' => 'required|string|max:1000',
            'notice_type_id' => 'nullable|exists:notice_types,id',
        ]);

        $notice->update($validated);
        return redirect()->route('notices.index')->with('success', __('dashboard.notice_updated_successfully'));
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return response()->json(['success' => __('Notice deleted successfully')]);
    }
}
