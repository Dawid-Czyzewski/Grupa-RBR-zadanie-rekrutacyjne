@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dodaj zadanie</h1>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        @include('tasks._form', ['task' => null])
    </form>
</div>
@endsection
