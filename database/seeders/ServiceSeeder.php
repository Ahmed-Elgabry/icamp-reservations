<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceReport;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'name' => 'Golden Camp V.I.P',
                'description' => 'المخيم الذهبي',
                'price' => 2000.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Silver Camp',
                'description' => 'المخيم الفضي',
                'price' => 1500.00,
                'hours' => 7,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Bronze Camp',
                'description' => 'المخيم البرونزي',
                'price' => 1200.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Out Side Camp',
                'description' => 'مخيم خارجي',
                'price' => 1500.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Combined All camp',
                'description' => '',
                'price' => 4700.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Golden Camp V.I.P 2',
                'description' => '2المخيم الذهبي',
                'price' => 2000.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Silver Camp 2',
                'description' => '2المخيم الفضي',
                'price' => 1500.00,
                'hours' => 7,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
            [
                'name' => 'Bronze Camp 2',
                'description' => '2المخيم البرونزي',
                'price' => 1200.00,
                'hours' => 1,
                'hour_from' => '08:00:00',
                'hour_to' => '17:00:00',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }

        foreach (Service::all() as $service) {
            foreach (campInventory() as $report => $count) {
                ServiceReport::updateOrCreate(
                    ['name' => $report, 'service_id' => $service->id],
                    [
                        'name' => $report,
                        'count' => $count,
                        'service_id' => $service->id
                    ]
                );
            }
        }
    }
}
