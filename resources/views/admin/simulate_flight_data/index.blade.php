@extends('layouts.admin')

@section('title', 'Тестовая телеметрия')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Симуляция координат дронов</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.simulate_flight_data.create') }}" class="btn btn-success mb-3">
        ➕ Добавить данные
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Дрон</th>
                    <th title="Широта в градусах">Широта</th>
                    <th title="Долгота в градусах">Долгота</th>
                    <th title="Высота полёта в метрах">Высота (м)</th>
                    <th title="Скорость в км/ч">Скорость</th>
                    <th title="Курс движения в градусах">Курс (°)</th>
                    <th title="Дата и время записи">Время</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flights as $flight)
                <tr>
                    <td>{{ $flight->id }}</td>
                    <td>{{ $flight->drone->name ?? 'ID: ' . $flight->drone_id }}</td>
                    <td>{{ number_format($flight->latitude, 6) }}</td>
                    <td>{{ number_format($flight->longitude, 6) }}</td>
                    <td>{{ $flight->altitude !== null ? number_format($flight->altitude, 1) : '—' }}</td>
                    <td>{{ $flight->speed !== null ? number_format($flight->speed, 1) : '—' }}</td>
                    <td>{{ $flight->heading !== null ? number_format($flight->heading, 1) : '—' }}</td>
                    <td>{{ $flight->created_at->format('Y-m-d H:i') }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.simulate_flight_data.edit', $flight) }}"
                           class="btn btn-sm btn-primary" title="Редактировать">
                            ✏️
                        </a>

                        <form action="{{ route('admin.simulate_flight_data.destroy', $flight) }}"
                              method="POST" onsubmit="return confirm('Удалить запись?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Удалить">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Данные полётов отсутствуют</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $flights->links() }}
    </div>
</div>
@endsection
