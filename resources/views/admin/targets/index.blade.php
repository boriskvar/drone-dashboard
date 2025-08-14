@extends('layouts.admin')

@section('title', 'Цели дронов')

@section('header', 'Цели дронов')

@section('content')
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.targets.create') }}" class="btn btn-primary mb-3">+ Добавить цель</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Дрон</th>
                <th>Широта (latitude)</th>
                <th>Долгота (longitude)</th>
                <th>Создана</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($targets as $target)
            <tr>
                <td>#{{ $target->drone->id ?? '—' }} {{ $target->drone->name ?? '—' }}</td>
                <td>{{ $target->latitude }}</td>
                <td>{{ $target->longitude }}</td>
                <td>{{ $target->created_at->format('d.m.Y H:i') }}</td>
                <td class="d-flex gap-1">
                    <a href="{{ route('admin.targets.edit', $target) }}" class="btn btn-sm btn-primary" title="Редактировать">✏️</a>

                    <form method="POST" action="{{ route('admin.targets.destroy', $target) }}" onsubmit="return confirm('Удалить цель?')" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Удалить">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Целей пока нет</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $targets->links() }}
</div>
@endsection