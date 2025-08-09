@extends('layouts.admin')

@section('title', 'Список дронов')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Список дронов</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.drones.create') }}" class="btn btn-primary mb-3">
        + Добавить дрон
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Статус</th>
                <th>Широта</th>
                <th>Долгота</th>
                <!-- <th>Модель</th> -->
                <!-- <th>Серийный номер</th> -->
                <!-- <th>Производитель</th> -->
                <!-- <th>Дата производства</th> -->
                <!-- <th>Версия прошивки</th> -->
                <!-- <th>Создан</th> -->
                <th>Обновлен</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drones as $drone)
            <tr>
                <td>{{ $drone->id }}</td>
                <td>{{ $drone->name }}</td>
                <td>{{ $drone->status }}</td>
                <td>{{ $drone->latitude }}</td>
                <td>{{ $drone->longitude }}</td>
                <!-- <td>{{ $drone->model ?? '-' }}</td> -->
                <!-- <td>{{ $drone->serial_number ?? '-' }}</td> -->
                <!-- <td>{{ $drone->manufacturer ?? '-' }}</td> -->
                <!-- <td>{{ $drone->manufacture_date ? $drone->manufacture_date->format('Y-m-d') : '-' }}</td> -->
                <!-- <td>{{ $drone->firmware_version ?? '-' }}</td> -->
                <!-- <td>{{ $drone->created_at->format('Y-m-d H:i') }}</td> -->
                <td>{{ $drone->updated_at->format('Y-m-d H:i') }}</td>
                <td class="d-flex gap-1">
                    <a href="{{ route('admin.drones.edit', $drone) }}" class="btn btn-sm btn-primary"
                       title="Редактировать">✏️</a>

                    <form action="{{ route('admin.drones.destroy', $drone) }}" method="POST"
                          onsubmit="return confirm('Удалить дрон?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Удалить">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $drones->links() }}
</div>
@endsection
