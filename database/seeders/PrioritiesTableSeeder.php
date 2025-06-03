<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Priority;

class PrioritiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'low'],
            ['name' => 'medium'],
            ['name' => 'high'],
        ];

        foreach ($priorities as $p) {
            Priority::updateOrCreate(
                ['name' => $p['name']],
                ['name' => $p['name']]
            );
        }
    }
}
