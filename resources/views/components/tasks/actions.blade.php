<div class="d-flex justify-content-between flex-wrap gap-2">
    <div class="btn-group">
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary">Edytuj</a>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Na pewno usunąć to zadanie?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Usuń</button>
        </form>
    </div>
    <div class="btn-group">
        <a href="{{ route('tasks.history', $task->id) }}" class="btn btn-outline-dark">Historia zmian</a>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Powrót do listy</a>
    </div>
</div>
