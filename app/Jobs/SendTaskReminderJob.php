<?php

namespace App\Jobs;

use App\Models\Task;
use App\Mail\TaskDueReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTaskReminderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $taskId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    public function handle(): void
    {
        $task = Task::with('user')->find($this->taskId);

        if ($task && $task->user && $task->user->email) {
            Mail::to($task->user->email)->send(new TaskDueReminderMail($task));
        }
    }
}
