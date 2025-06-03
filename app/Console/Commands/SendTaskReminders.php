<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendTaskReminderJob;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Wysyła przypomnienia o zadaniach, których termin przypada jutro.';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $tasks = Task::with('user')
            ->whereDate('due_date', $tomorrow)
            ->get();

        foreach ($tasks as $task) {
            $userEmail = $task->user?->email;

            if (!$userEmail) {
                $this->warn("Zadanie ID {$task->id} nie ma przypisanego użytkownika z e-mailem.");
                continue;
            }

            try {
                SendTaskReminderJob::dispatch($task->id);

                $this->info("Przypomnienie dla zadania ID {$task->id} wysłane do {$userEmail}");
                Log::info("Przypomnienie wysłane dla zadania ID {$task->id} do {$userEmail}");
            } catch (\Throwable $e) {
                $this->error("Błąd wysyłania przypomnienia dla zadania ID {$task->id}: {$e->getMessage()}");
                Log::error("Błąd przypomnienia (ID {$task->id}): " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
