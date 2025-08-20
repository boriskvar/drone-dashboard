@extends('layouts.admin')

@section('title', 'Симуляция движения дронов')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Симуляция движения дронов</h1>

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
@endsection
