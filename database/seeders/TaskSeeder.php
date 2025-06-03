<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Status;
use App\Models\Priority;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Przykładowi użytkownicy i statusy/priorities muszą istnieć
        $user = User::first(); // lub stwórz użytkownika na sztywno
        $statuses = Status::pluck('id')->all();
        $priorities = Priority::pluck('id')->all();

        if (!$user || empty($statuses) || empty($priorities)) {
            $this->command->warn('Brak danych referencyjnych: user, statusy, priorytety.');
            return;
        }

        // 2 zadania z jutrzejszą datą
        foreach (range(1, 2) as $i) {
            Task::create([
                'name' => "Jutrzejsze zadanie $i",
                'description' => "Opis zadania $i",
                'due_date' => Carbon::now()->addDay()->toDateString(),
                'status_id' => $statuses[array_rand($statuses)],
                'priority_id' => $priorities[array_rand($priorities)],
                'user_id' => $user->id,
            ]);
        }

        // 8 losowych zadań z różnymi datami
        foreach (range(3, 10) as $i) {
            Task::create([
                'name' => "Zadanie $i",
                'description' => "Opis zadania $i",
                'due_date' => Carbon::now()->addDays(rand(-5, 10))->toDateString(),
                'status_id' => $statuses[array_rand($statuses)],
                'priority_id' => $priorities[array_rand($priorities)],
                'user_id' => $user->id,
            ]);
        }
    }
}
