@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h2 class="h5">{{ $task->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Opis:</strong> {{ $task->description ?? 'Brak opisu' }}</p>
            <p><strong>Priorytet:</strong> {{ ucfirst($task->priority->name) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($task->status->name) }}</p>
            <p><strong>Termin wykonania:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
            <p class="text-muted"><small>Ten widok jest publiczny, bez logowania. Link ważny do: <strong>{{ $task->share_token_expires_at->format('Y-m-d H:i') }}</strong>.</small></p>
        </div>
    </div>
</div>
@endsection
