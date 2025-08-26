@extends('layouts.admin')

@section('content')
<h2>Просмотр обнаружения #{{ $detection->id }}</h2>

<div class="card mb-3">
    <div class="card-body">
        <p><strong>ID:</strong> {{ $detection->id }}</p>

        <p><strong>Картинка:</strong><br>
            @if($detection->image && $detection->image->path)
            <img src="{{ asset('storage/' . $detection->image->path) }}" width="300" alt="image">
            @else
            —
            @endif
        </p>

        <p><strong>Цель:</strong> {{ $detection->target }}</p>
        <p><strong>Координаты:</strong> ({{ $detection->x1 }}, {{ $detection->y1 }}) — ({{ $detection->x2 }},
            {{ $detection->y2 }})</p>
        <p><strong>Дата:</strong> {{ $detection->created_at->format('d.m.Y H:i') }}</p>
    </div>
</div>

<a href="{{ route('admin.detections.edit', $detection) }}" class="btn btn-warning">Редактировать</a>
<a href="{{ route('admin.detections.index') }}" class="btn btn-secondary">Назад</a>
@endsection
