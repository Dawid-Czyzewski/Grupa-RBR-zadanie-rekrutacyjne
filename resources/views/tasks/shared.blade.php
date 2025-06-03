@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4">Zadanie publiczne</h1>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">{{ $task->name }}</h5>
            <p class="card-text">{{ $task->description }}</p>
            <p><strong>Termin:</strong> {{ $task->due_date->format('d.m.Y') }}</p>
        </div>
    </div>
</div>
@endsection
