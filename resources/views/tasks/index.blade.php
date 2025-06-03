@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Nagłówek i przycisk dodawania --}}
        @include('components.tasks.header')

        {{-- Komunikat sukcesu --}}
        @include('components.common.success')

        {{-- Formularz filtrów --}}
        @include('components.tasks.filters', [
            'priorities' => $priorities,
            'statuses' => $statuses
        ])

        {{-- Tabela zadań --}}
        @include('components.tasks.table', [
            'tasks' => $tasks
        ])
    </div>
@endsection
