@extends('layouts.admin')

@section('title', 'Список координат')

@section('header', 'Координаты дронов')

@section('content')
<h1>Координаты дронов</h1>

<a href="{{ route('admin.positions.create') }}" class="btn btn-success mb-3">Добавить координаты</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дрон</th>
            <th>Широта</th>
            <th>Долгота</th>
            <th>Время</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($positions as $position)
        <tr>
            <td>{{ $position->id }}</td>
            <td>{{ $position->drone->name ?? '—' }}</td>
            <td>{{ $position->lat }}</td>
            <td>{{ $position->lng }}</td>
            <td>{{ $position->created_at->format('Y-m-d H:i:s') }}</td>
            <td>
                <a href="{{ route('admin.positions.edit', $position->id) }}"
                   class="btn btn-sm btn-primary">Редактировать</a>

                <form action="{{ route('admin.positions.destroy', $position->id) }}" method="POST"
                      style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Удалить позицию?')">Удалить</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

{{-- Пагинация --}}
{{ $positions->links() }}
@endsection
