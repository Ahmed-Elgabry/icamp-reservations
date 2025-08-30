<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskTypes = [
            [
                'name' => 'Maintenance',
                'description' => 'Equipment and facility maintenance tasks',
                'status' => 'active',
            ],
            [
                'name' => 'Inspection',
                'description' => 'Safety and quality inspection tasks',
                'status' => 'active',
            ],
            [
                'name' => 'Cleaning',
                'description' => 'Camp area and facility cleaning tasks',
                'status' => 'active',
            ],
            [
                'name' => 'Setup',
                'description' => 'Event and camp setup preparation tasks',
                'status' => 'active',
            ],
            [
                'name' => 'Administrative',
                'description' => 'Office and administrative tasks',
                'status' => 'active',
            ],
            [
                'name' => 'Emergency',
                'description' => 'Emergency response and safety tasks',
                'status' => 'active',
            ],
        ];

        foreach ($taskTypes as $taskType) {
            TaskType::create($taskType);
        }
    }
}
