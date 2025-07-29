@extends('layouts.admin')

@section('title', 'Список дронов')

@section('header', 'Дроны')

@section('content')
<h1>Список дронов</h1>

<a href="{{ url('/admin/drones/create') }}" class="btn btn-success mb-3">Добавить дрон</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Статус</th>
            <th>Широта</th>
            <th>Долгота</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($drones as $drone)
        <tr>
            <td>{{ $drone->id }}</td>
            <td>{{ $drone->name }}</td>
            <td>{{ $drone->status }}</td>
            <td>{{ $drone->lat }}</td>
            <td>{{ $drone->lng }}</td>
            <td>
                <a href="{{ url("/admin/drones/{$drone->id}/edit") }}" class="btn btn-sm btn-primary">Редактировать</a>

                <form action="{{ url("/admin/drones/{$drone->id}") }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Удалить?')">Удалить</button>
                </form>


                <!-- Кнопка "Сдвинуть" -->
                <form action="{{ url('/admin/drones/' . $drone->id . '/move') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">Сдвинуть</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
