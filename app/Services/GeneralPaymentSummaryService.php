<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GeneralPaymentSummaryService
{
    /**
     * Get order payment summaries based on filters
     *
     * @param Request $request
     * @return Collection
     */
    public function getOrderPaymentSummaries(Request $request): Collection
    {
        $query = $this->buildTransactionQuery($request);
        
        $general_payments = $query->where('verified', "1")->get();
        $paymentsByOrder = $general_payments->groupBy('order_id');
        
        return $this->processOrderSummaries($paymentsByOrder);
    }

    /**
     * Build the transaction query with filters
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildTransactionQuery(Request $request)
    {
        $query = Transaction::with([
                'order.customer',
                'order.expenses' => fn ($q) => $q->where('verified', true),
                'order.addons'  => fn ($q) => $q->where('verified', true),
                'order.items'  => fn ($q) => $q->where('verified', true)
            ])
            ->where(fn ($q) =>
                $q->where('source', "insurances")
                ->where("amount",">" ,0)
                // ->whereHas("order", fn ($q) => $q->where('insurance_status', "1")) // this is corresponding with the button in verfication in order return insurance
                ->orWhere('source', 'reservation_addon')
                ->orWhere('source', 'warehouse_sale')
            );

        // Apply filters
        if ($request->customer_id) {
            $query->whereHas('order.customer', function($q) use ($request) {
                $q->where('id', $request->customer_id);
            });
        }

        if ($request->order_id) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->date_from && $request->date_to) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        return $query;
    }

    /**
     * Process order summaries from grouped payments
     *
     * @param Collection $paymentsByOrder
     * @return Collection
     */
    private function processOrderSummaries(Collection $paymentsByOrder): Collection
    {
        $orderSummaries = collect();
        
        foreach ($paymentsByOrder as $orderId => $orderTransactions) {
            $order = $orderTransactions->first()?->order;
            
            // Skip if order is null (orphaned transactions)
            if (!$order || !$order->customer) continue;
            
            // Group transactions by source for this specific order
            $insurances = $orderTransactions->where('source', 'insurances');
            $addonTransactions = $orderTransactions->where('source', 'reservation_addon');
            $warehouseTransactions = $orderTransactions->where('source', 'warehouse_sale');
            
            // Calculate totals from transactions (using 'amount' field)
            $insurancesTotal = $insurances->sum('amount');
            $addonsTotal = $addonTransactions->sum('amount');
            $warehouseTotal = $warehouseTransactions->sum('amount');
            
            // Count items for this order
            $insurancesCount = $insurances->count();
            $addonsCount = $addonTransactions->count();
            $warehouseCount = $warehouseTransactions->count();

            $grandTotal = $insurancesTotal + $addonsTotal + $warehouseTotal;

            // Get latest transaction date for this order
            $latestDate = $orderTransactions->max('created_at');

            $orderSummaries->push((object)[
                'order' => $order,
                'customer' => $order->customer,
                'insurance_total' => $insurancesTotal,
                'insurance_count' => $insurancesCount,
                'addons_total' => $addonsTotal,
                'addons_count' => $addonsCount,
                'warehouse_total' => $warehouseTotal,
                'warehouse_count' => $warehouseCount,
                'grand_total' => $grandTotal,
                'latest_date' => $latestDate
            ]);
        }
        
        return $orderSummaries;
    }

    /**
     * Get filtered orders for dropdown
     *
     * @return Collection
     */
    public function getFilteredOrders(): Collection
    {
        return Order::whereNot('insurance_status', 'returned')->get();
    }

    /**
     * Get customers for dropdown
     *
     * @return Collection
     */
    public function getCustomersForFilter(): Collection
    {
        return Customer::orderBy('name')->get(['id', 'name']);
    }

    /**
     * Get payment summary statistics
     *
     * @param Collection $orderSummaries
     * @return array
     */
    public function getPaymentStatistics(Collection $orderSummaries): array
    {
        return [
            'total_orders' => $orderSummaries->count(),
            'total_insurance_amount' => $orderSummaries->sum('insurance_total'),
            'total_addons_amount' => $orderSummaries->sum('addons_total'),
            'total_warehouse_amount' => $orderSummaries->sum('warehouse_total'),
            'grand_total_amount' => $orderSummaries->sum('grand_total'),
            'average_order_value' => $orderSummaries->count() > 0 ? $orderSummaries->avg('grand_total') : 0,
        ];
    }

    /**
     * Get payment summaries with statistics
     *
     * @param Request $request
     * @return array
     */
    public function getPaymentSummariesWithStats(Request $request): array
    {
        $orderSummaries = $this->getOrderPaymentSummaries($request);
        $statistics = $this->getPaymentStatistics($orderSummaries);
        $orders = $this->getFilteredOrders();
        $customers = $this->getCustomersForFilter();

        return [
            'order_summaries' => $orderSummaries,
            'statistics' => $statistics,
            'orders' => $orders,
            'customers' => $customers,
        ];
    }
}
