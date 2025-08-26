@extends('layouts.admin')

@section('content')
<h2>Обнаружения</h2>

<div class="mb-3">
    <a href="{{ route('admin.detections.create') }}" class="btn btn-primary">+ Добавить обнаружение</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Картинка</th>
            <th>Цель</th>
            <th>Координаты (x1, y1, x2, y2)</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($detections as $detection)
        <tr>
            <td>{{ $detection->id }}</td>
            <td>
                @if ($detection->image && $detection->image->path)
                <img src="{{ asset('storage/' . $detection->image->path) }}"
                     width="100"
                     alt="image">
                @else
                —
                @endif
            </td>
            <td>{{ $detection->target }}</td>
            <td>
                ({{ $detection->x1 }}, {{ $detection->y1 }})
                —
                ({{ $detection->x2 }}, {{ $detection->y2 }})
            </td>
            <td>{{ $detection->created_at->format('d.m.Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.detections.show', $detection) }}" class="btn btn-sm btn-info"
                   title="Просмотр">👁️</a>
                <a href="{{ route('admin.detections.edit', $detection) }}"
                   class="btn btn-sm btn-warning" title="Редактировать">✏️</a>
                <form action="{{ route('admin.detections.destroy', $detection) }}"
                      method="POST"
                      style="display:inline-block;"
                      onsubmit="return confirm('Удалить это обнаружение?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit" title="Удалить">🗑️</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Обнаружений пока нет</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $detections->links() }}
@endsection
