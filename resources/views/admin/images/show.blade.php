@extends('layouts.admin')

@section('title', 'Просмотр картинки')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Просмотр картинки #{{ $image->id }}</h1>

    <div class="mb-3">
        <img src="{{ asset('storage/'.$image->path) }}" width="400" alt="image">
    </div>

    <div class="mb-3">
        <strong>Название:</strong> {{ $image->title ?? '—' }}
    </div>

    <div class="mb-3">
        <strong>Обнаружения:</strong>
        @if($image->detections->count())
        <ul>
            @foreach($image->detections as $det)
            <li>{{ $det->target }}: ({{ $det->x1 }},{{ $det->y1 }}) → ({{ $det->x2 }},{{ $det->y2 }})</li>
            @endforeach
        </ul>
        @else
        <p>Нет обнаружений</p>
        @endif
    </div>

    <a href="{{ route('admin.images.index') }}" class="btn btn-secondary mt-2">Назад к списку</a>
    <a href="{{ route('admin.images.edit', $image) }}" class="btn btn-primary mt-2">Редактировать</a>
</div>
@endsection
