<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentLink;
use App\Models\Customer;
use App\Models\Order;

class PaymentLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // الحصول على عملاء موجودين
        $customers = Customer::take(5)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('لا توجد عملاء في قاعدة البيانات. تأكد من تشغيل CustomerSeeder أولاً.');
            return;
        }

        // الحصول على طلبات موجودة
        $orders = Order::take(3)->get();

        $this->command->info('بدء إنشاء بيانات تجريبية لروابط الدفع...');

        foreach ($customers as $index => $customer) {
            $order = $orders->get($index);

            PaymentLink::create([
                'order_id' => $order ? $order->id : null,
                'customer_id' => $customer->id,
                'amount' => rand(100, 5000),
                'description' => 'دفع تجريبي للعميل ' . $customer->name,
                'checkout_id' => 'TEST_' . uniqid(),
                'checkout_key' => 'KEY_' . uniqid(),
                'payment_url' => 'https://pay.test.paymennt.com/checkout/' . uniqid(),
                'status' => $this->getRandomStatus(),
                'paid_at' => $this->getRandomPaidAt(),
                'expires_at' => now()->addDays(rand(1, 30)),
                'request_id' => 'REQ_' . uniqid(),
                'order_id_paymennt' => 'ORD_' . uniqid(),
            ]);

            $this->command->info("تم إنشاء رابط دفع للعميل: {$customer->name}");
        }

        $this->command->info('تم إنشاء بيانات تجريبية لروابط الدفع بنجاح!');
    }

    /**
     * الحصول على حالة عشوائية
     */
    private function getRandomStatus()
    {
        $statuses = ['pending', 'paid', 'cancelled', 'expired'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * الحصول على تاريخ دفع عشوائي
     */
    private function getRandomPaidAt()
    {
        $status = $this->getRandomStatus();

        if ($status === 'paid') {
            return now()->subDays(rand(1, 30));
        }

        return null;
    }
}
