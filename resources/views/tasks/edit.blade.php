@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h1 class="h4 mb-0">Edytuj zadanie</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @method('PUT')
                @include('tasks._form')
            </form>
        </div>
    </div>
</div>
@endsection
