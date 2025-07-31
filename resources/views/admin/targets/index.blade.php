@extends('layouts.admin')

@section('content')
<h2>Цели дронов</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Дрон</th>
            <th>Координаты</th>
            <th>Создана</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($targets as $target)
        <tr>
            <td>#{{ $target->drone->id }} {{ $target->drone->name }}</td>
            <td>{{ $target->lat }}, {{ $target->lng }}</td>
            <td>{{ $target->created_at->format('d.m.Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.targets.edit', $target->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                <form method="POST" action="{{ route('admin.targets.destroy', $target->id) }}" style="display:inline-block;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить цель?')">Удалить</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection