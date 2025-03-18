<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Feature','color_code' => '#00000'],
            ['name' => 'Bug','color_code' => '#00000']
        ];

        foreach ($types as $type) {
            TaskType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
