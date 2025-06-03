@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Historia zmian: {{ $task->name }}</h2>

    <a href="{{ route('tasks.index') }}" class="btn btn-secondary mb-3">← Powrót do listy zadań</a>

    @if ($task->histories && $task->histories->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data (zmiana)</th>
                        <th>Użytkownik</th>
                        <th>Pole</th>
                        <th>Stara wartość</th>
                        <th>Nowa wartość</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($task->histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $history->user->name ?? 'Nieznany' }}</td>
                            <td>{{ $history->field }}</td>
                            <td>
                                @if ($history->field === 'priority_id')
                                    {{ \App\Models\Priority::find($history->old_value)?->name ?? $history->old_value }}
                                @elseif ($history->field === 'status_id')
                                    {{ \App\Models\Status::find($history->old_value)?->name ?? $history->old_value }}
                                @elseif ($history->field === 'due_date')
                                    {{ \Carbon\Carbon::parse($history->old_value)->format('Y-m-d') }}
                                @else
                                    {{ $history->old_value }}
                                @endif
                            </td>
                            <td>
                                @if ($history->field === 'priority_id')
                                    {{ \App\Models\Priority::find($history->new_value)?->name ?? $history->new_value }}
                                @elseif ($history->field === 'status_id')
                                    {{ \App\Models\Status::find($history->new_value)?->name ?? $history->new_value }}
                                @elseif ($history->field === 'due_date')
                                    {{ \Carbon\Carbon::parse($history->new_value)->format('Y-m-d') }}
                                @else
                                    {{ $history->new_value }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">Brak historii zmian dla tego zadania.</div>
    @endif
</div>
@endsection
