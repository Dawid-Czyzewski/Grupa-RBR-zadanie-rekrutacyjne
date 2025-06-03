<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PrioritiesTableSeeder::class,
            StatusesTableSeeder::class,
        ]);

        $this->call([
            UsersTableSeeder::class,
        ]);
    }
}
