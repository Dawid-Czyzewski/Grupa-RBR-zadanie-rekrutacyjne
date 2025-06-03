<div class="card">
    <div class="card-body p-0">
        @if($tasks->isEmpty())
            <p class="p-3 text-muted">Brak zadań do wyświetlenia.</p>
        @else
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nazwa</th>
                        <th>Priorytet</th>
                        <th>Status</th>
                        <th>Termin</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ ucfirst($task->priority->name) }}</td>
                            <td>{{ ucfirst($task->status->name) }}</td>
                            <td>{{ $task->due_date->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary me-1">Zobacz</a>
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary me-1">Edytuj</a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Na pewno usunąć to zadanie?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
