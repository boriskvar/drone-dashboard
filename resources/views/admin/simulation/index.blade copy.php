@extends('layouts.admin')

@section('title', 'Симуляция движения дронов')

@section('content')

{{-- @php
dd($drones->toArray()); // Debugging line to check drones data
@endphp --}}

<div class="container mt-4">
    <h1 class="mb-4">Симуляция движения дронов</h1>

    {{-- Debug --}}
    {{-- <pre>
        {{ print_r($drones->toArray(), true) }}
    </pre> --}}

    <!-- Vue-компонент с передачей всех дронов -->
    <div id="app">
        <simulation-map :drones='@json($drones)'></simulation-map>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Широта</th>
                <th>Долгота</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drones as $drone)
            <tr>
                <td>{{ $drone->id }}</td>
                <td>{{ $drone->name }}</td>
                <td>{{ $drone->latitude }}</td>
                <td>{{ $drone->longitude }}</td>
                <td class="d-flex gap-2">
                    <form action="{{ route('admin.simulation.start', $drone) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-success">▶️ Старт</button>
                    </form>

                    <form action="{{ route('admin.simulation.stop', $drone) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger">⏹ Стоп</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Подключение сборки Vite -->
@vite('resources/js/app.js')

@endsection
