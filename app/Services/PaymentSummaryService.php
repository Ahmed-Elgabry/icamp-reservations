<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Addon;
use App\Models\WarehouseItem;
use App\Models\SurveyResponse;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentSummaryService
{
    /**
     * Get payment records for different transaction sources
     * 
     * Source values and their meanings:
     * - 'reservation_addon': Transactions related to add-ons purchased with reservations
     * - 'warehouse_sale': Transactions for warehouse sales
     * - 'reservation_payments': Payments made for reservations such as deposit and insurance  and complete payment
     *
     * @param int $limit Number of records per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaymentRecords($limit = 15)
    {
        // Filter transactions by specific source types related to payments
        $query = Transaction::where('source', 'reservation_addon')
                            ->orWhere('source', 'warehouse_sale')
                            ->orWhere('source', 'reservation_payments')
                            ->orWhere('source', 'insurances');

        return $query->with([
                'order',
                'customer' ,
                'order.addons',
                'order.items',
                'payment',
                'expense'
            ])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get all dashboard data including payments, surveys, low stock items, and expenses
     * 
     * @return array
     */
    public function getDashboardData()
    {
        return [
            'paymentRecords' => $this->getPaymentRecords(10),
            'surveyResponses' => $this->getRecentSurveyResponses(),
            'lowStockItems' => $this->getLowStockItems(),
            'recentExpenses' => $this->getRecentExpenses(),
            'recentAddFunds' => $this->getRecentAddFunds()
        ];
    }

    /**
     * Get recent survey responses with customer and reservation information
     * 
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentSurveyResponses($limit = 5)
    {
        return SurveyResponse::with([
                'reservation' => function($query) {
                    $query->select('id', 'customer_id')
                          ->with(['customer' => function($q) {
                              $q->select('id', 'name');
                          }]);
                },
                'survey' => function($query) {
                    $query->select('id', 'title');
                }
            ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get low stock items (quantity <= 10)
     * 
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getLowStockItems($limit = 10)
    {
        return Stock::where('quantity', '<=', 10)
            ->orderBy('quantity')
            ->take($limit)
            ->get();
    }

    /**
     * Get recent expenses from transactions
     * 
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentExpenses($limit = 10)
    {
        return Transaction::whereIn('source', ['reservation_expenses', 'general_expenses'])
            ->with([
                'expense',
                'account' 
            ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get recent add funds transactions
     * 
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentAddFunds($limit = 10)
    {
        return Transaction::where('source', 'add_funds_page')
            ->with([
                'generalPayment',
                'account' ,
                'receiver'
            ])
            ->latest()
            ->take($limit)
            ->get();
    }
}
