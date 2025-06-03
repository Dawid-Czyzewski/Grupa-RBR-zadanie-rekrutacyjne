<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'to-do'],
            ['name' => 'in progress'],
            ['name' => 'done'],
        ];

        foreach ($statuses as $s) {
            Status::updateOrCreate(
                ['name' => $s['name']],
                ['name' => $s['name']]
            );
        }
    }
}
