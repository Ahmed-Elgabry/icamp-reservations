<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Stock;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get existing customers, services, users, and stocks
        $customers = Customer::all();
        $services = Service::all();
        $users = User::all();
        $stocks = Stock::all();

        // If no data exists, create some basic data first
        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Creating customers first...');
            $this->call(CustomerSeeder::class);
            $customers = Customer::all();
        }

        if ($services->isEmpty()) {
            $this->command->warn('No services found. Creating services first...');
            $this->call(ServiceSeeder::class);
            $services = Service::all();
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Creating users first...');
            $this->call(RolesAndPermissionsSeeder::class);
            $users = User::all();
        }

        if ($stocks->isEmpty()) {
            $this->command->warn('No stocks found. Creating stocks first...');
            $this->call(StockSeeder::class);
            $stocks = Stock::all();
        }

        $this->seedOrders($faker, $customers, $services, $users, $stocks);
    }

    /**
     * Seed orders independently
     */
    public function seedOrders($faker = null, $customers = null, $services = null, $users = null, $stocks = null)
    {
        if (!$faker) {
            $faker = Faker::create();
        }

        if (!$customers) {
            $customers = Customer::all();
        }

        if (!$services) {
            $services = Service::all();
        }

        if (!$users) {
            $users = User::all();
        }

        if (!$stocks) {
            $stocks = Stock::all();
        }

        // Create sample orders
        $statuses = ['pending_and_show_price', 'pending_and_Initial_reservation', 'approved', 'canceled', 'delayed', 'completed'];
        $insuranceStatuses = ['returned', 'confiscated_full', 'confiscated_partial'];

        $this->command->info('Creating 20 sample orders...');

        for ($i = 1; $i <= 20; $i++) {
            $customer = $customers->random();
            $user = $users->random();
            $service = $services->random();

            // Generate random dates (within last 6 months)
            $orderDate = $faker->dateTimeBetween('-6 months', 'now');
            $timeFrom = $faker->time('H:i:s');
            $timeTo = Carbon::parse($timeFrom)->addHours($faker->numberBetween(1, 8));

            // Calculate base price based on service
            $basePrice = $service->price ?? $faker->randomFloat(2, 500, 3000);
            $deposit = $faker->randomFloat(2, 0, $basePrice * 0.3);
            $insuranceAmount = $faker->randomFloat(2, 0, $basePrice * 0.2);

            $order = Order::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'date' => $orderDate->format('Y-m-d'),
                    'time_from' => $timeFrom,
                ],
                [
                    'price' => $basePrice,
                    'deposit' => $deposit,
                    'insurance_amount' => $insuranceAmount,
                    'notes' => $faker->optional(0.7)->sentence(),
                    'time_to' => $timeTo->format('H:i:s'),
                    'time_of_receipt' => $faker->optional(0.6)->time('H:i:s'),
                    'time_of_receipt_notes' => $faker->optional(0.5)->sentence(),
                    'delivery_time' => $faker->optional(0.6)->time('H:i:s'),
                    'delivery_time_notes' => $faker->optional(0.5)->sentence(),
                    'voice_note' => null, // Removed to avoid potential file handling issues
                    'video_note' => null, // Removed to avoid potential file handling issues
                    'image_before_receiving' => null, // Removed to avoid UploadTrait issues
                    'image_after_delivery' => null, // Removed to avoid UploadTrait issues
                    'status' => $faker->randomElement($statuses),
                    'refunds' => $faker->randomElement(['0', '1']), // Use strings for enum
                    'refunds_notes' => $faker->optional(0.3)->sentence(),
                    'delayed_time' => $faker->optional(0.2)->time('H:i:s'),
                    'inventory_withdrawal' => $faker->randomElement(['0', '1']), // Use strings for enum
                    'insurance_status' => $faker->optional(0.4)->randomElement($insuranceStatuses),
                    'confiscation_description' => $faker->optional(0.3)->sentence(),
                    'report_text' => $faker->optional(0.6)->paragraph(),
                    'created_by' => $user->id,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]
            );

            // Create order items (stock items)
            if ($stocks->isNotEmpty() && $faker->boolean(70)) {
                $stock = $stocks->random();
                $quantity = $faker->randomFloat(3, 0.1, 10);
                $unitPrice = $stock->price ?? $faker->randomFloat(2, 5, 100);
                $totalPrice = $quantity * $unitPrice;

                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'stock_id' => $stock->id,
                    ],
                    [
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'notes' => $faker->optional(0.4)->sentence(),
                    ]
                );
            }

            // Attach services to order
            if ($faker->boolean(80)) {
                $order->services()->attach($service->id, [
                    'price' => $basePrice
                ]);
            }

            $this->command->info("Created order #{$i} for customer: {$customer->name}");
        }

        $this->command->info('Orders seeded successfully!');
    }
}
