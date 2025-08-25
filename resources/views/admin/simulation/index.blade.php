@extends('layouts.admin')

@section('title', 'Симуляция движения дронов')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Симуляция движения дронов</h1>

    {{-- Сообщения об успехе/ошибке --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <!-- Vue-компонент с картой -->
    <div id="app">
        <simulation-map :drones='@json($drones)'></simulation-map>
    </div>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Широта</th>
                <th>Долгота</th>
                <th>Статус</th>
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
                <td>
                    @if($drone->is_simulating)
                    <span class="badge bg-success">В полёте</span>
                    @else
                    <span class="badge bg-secondary">Стоит</span>
                    @endif
                </td>
                <td class="d-flex gap-2">
                    @if(!$drone->is_simulating)
                    <!-- 🚀 Запустить симуляцию -->
                    <form action="{{ route('admin.simulation.start', $drone) }}" method="POST"
                          onsubmit="return confirm('Запустить симуляцию для дрона {{ $drone->name }}?')">
                        @csrf
                        <button class="btn btn-sm btn-success">▶️ Старт</button>
                    </form>
                    @else
                    <!-- 🛑 Остановить симуляцию -->
                    <form action="{{ route('admin.simulation.stop', $drone) }}" method="POST"
                          onsubmit="return confirm('Остановить симуляцию для дрона {{ $drone->name }}?')">
                        @csrf
                        <button class="btn btn-sm btn-danger">⏹ Стоп</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Подключение сборки Vite -->
@vite('resources/js/app.js')

@endsection
