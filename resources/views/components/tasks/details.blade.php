<div class="card mb-4">
    <div class="card-header">
        <h2 class="h5 mb-0">{{ $task->name }}</h2>
    </div>
    <div class="card-body">
        <p><strong>Opis:</strong> {{ $task->description ?? 'Brak opisu' }}</p>
        <p><strong>Priorytet:</strong> {{ ucfirst($task->priority->name) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($task->status->name) }}</p>
        <p><strong>Termin:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
    </div>
</div>
