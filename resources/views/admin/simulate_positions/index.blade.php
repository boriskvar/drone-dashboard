@extends('layouts.admin')

@section('title', 'Тестовая телеметрия')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Симуляция координат дронов</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.simulate_positions.create') }}" class="btn btn-primary mb-3">
        + Добавить позицию
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Дрон</th>
                <th>Широта</th>
                <th>Долгота</th>
                <th>Высота (м)</th>
                <th>Скорость (км/ч)</th>
                <th>Курс (°)</th>
                <th>Время</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
            <tr>
                <td>{{ $position->id }}</td>
                <td>{{ $position->drone->name ?? 'ID: ' . $position->drone_id }}</td>
                <td>{{ $position->latitude }}</td>
                <td>{{ $position->longitude }}</td>
                <td>{{ $position->altitude ?? '-' }}</td>
                <td>{{ $position->speed ?? '-' }}</td>
                <td>{{ $position->heading ?? '-' }}</td>
                <td>{{ $position->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="d-flex gap-1">
                    <a href="{{ route('admin.simulate_positions.edit', $position) }}"
                        class="btn btn-sm btn-primary">✏️</a>
                    <form action="{{ route('admin.simulate_positions.destroy', $position) }}" method="POST"
                        onsubmit="return confirm('Удалить запись?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $positions->links() }}
</div>
@endsection
