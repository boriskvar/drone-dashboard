@extends('layouts.admin')

@section('title', 'Телеметрия')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Телеметрия дронов</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.telemetries.create') }}" class="btn btn-primary mb-3">
        + Добавить координаты
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дрон</th>
                <th>Широта</th>
                <th>Долгота</th>
                <th>Высота</th>
                <th>Скорость (км/ч)</th>
                <th>Курс (°)</th>
                <th>Время</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($telemetries as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->drone->name ?? '—' }}</td>
                <td>{{ $t->latitude }}</td>
                <td>{{ $t->longitude }}</td>
                <td>{{ $telemetry->altitude ?? '-' }}</td>
                <td>{{ $telemetry->speed ?? '-' }}</td>
                <td>{{ $telemetry->heading ?? '-' }}</td>
                <td>{{ $telemetry->created_at->format('Y-m-d H:i:s') }}</td>
                <td>
                    <a href="{{ route('admin.telemetries.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">
                        Редактировать
                    </a>

                    <form action="{{ route('admin.telemetries.destroy', $t->id) }}" method="POST" style="display: inline-block;"
                        onsubmit="return confirm('Удалить запись #{{ $t->id }}?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Удалить</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $telemetries->links() }}
</div>
@endsection
