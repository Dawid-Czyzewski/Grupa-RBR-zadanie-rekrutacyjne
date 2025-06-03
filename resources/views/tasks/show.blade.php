@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <x-tasks.details :task="$task" />
    <x-tasks.share :task="$task" />
    <x-tasks.actions :task="$task" />
</div>
@endsection
