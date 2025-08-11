@extends('layouts.admin')

@section('title', 'Список координат')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Координаты дронов</h1>

    <a href="{{ route('admin.simulate_tracks.create') }}" class="btn btn-primary mb-3">
        + Добавить координаты
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
            @foreach ($simulateTracks as $track)
            <tr>
                <td>{{ $track->id }}</td>
                <td>{{ $track->drone->name ?? '—' }}</td>
                <td>{{ $track->latitude }}</td>
                <td>{{ $track->longitude }}</td>
                <td>{{ $track->altitude ?? '-' }}</td>
                <td>{{ $track->speed ?? '-' }}</td>
                <td>{{ $track->heading ?? '-' }}</td>
                <td>{{ $track->created_at->format('Y-m-d H:i') }}</td>
                <td class="d-flex gap-1">
                    <a href="{{ route('admin.simulate_tracks.edit', $track) }}" class="btn btn-sm btn-primary"
                       title="Редактировать">✏️</a>

                    <form action="{{ route('admin.simulate_tracks.destroy', $track) }}" method="POST"
                          onsubmit="return confirm('Удалить запись?')" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Удалить">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $simulateTracks->links() }}
</div>
@endsection
