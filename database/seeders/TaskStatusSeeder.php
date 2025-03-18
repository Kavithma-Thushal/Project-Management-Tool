<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Pending'],
            ['name' => 'On Development'],
            ['name' => 'Development Completed'],
            ['name' => 'Pushed to Git'],
            ['name' => 'Submit to QA'],
            ['name' => 'QA Verified'],
            ['name' => 'Reopening'],
        ];

        foreach ($types as $type) {
            TaskStatus::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
