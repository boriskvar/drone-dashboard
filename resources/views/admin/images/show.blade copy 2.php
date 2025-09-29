@extends('layouts.admin')

@section('title', 'Просмотр картинки')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Просмотр картинки #{{ $image->id }}</h1>

    <div class="mb-3" style="position: relative; display: inline-block; max-width: 100%;">
        <img src="{{ asset('storage/'.$image->path) }}" style="max-width: 100%; height: auto;" alt="image">

        {{-- Визуализация рамок --}}
        @foreach($image->detections as $det)
        @php
        $emoji = match($det->target) {
        'танк' => '🛡️',
        'багги' => '🚙',
        'солдат' => '🪖',
        'артиллерия' => '💣',
        default => '🎯'
        };
        @endphp
        @php
        // Масштабируем координаты под отображаемую ширину картинки
        $scale = $image->width > 600 ? 600 / $image->width : 1;
        $x1 = $det->x1 * $scale;
        $y1 = $det->y1 * $scale;
        $width = ($det->x2 - $det->x1) * $scale;
        $height = ($det->y2 - $det->y1) * $scale;
        @endphp
        <div style="
                    position: absolute;
                    top: {{ $det->y1 }}px;
                    left: {{ $det->x1 }}px;
                    width: {{ $det->x2 - $det->x1 }}px;
                    height: {{ $det->y2 - $det->y1 }}px;
                    border: 2px solid red;
                    background: rgba(255,0,0,0.2);
                ">
            <span style="
                        position: absolute;
                        top: -20px;
                        left: 0;
                        background: red;
                        color: white;
                        font-size: 13px;
                        font-weight: bold;
                        padding: 2px 6px;
                        border-radius: 3px;
                    ">
                {{ $emoji }} {{ $det->target }}
            </span>
        </div>
        @endforeach
    </div>

    <div class="mb-3 mt-4">
        <strong>Название:</strong> {{ $image->title ?? '—' }}
    </div>

    <div class="mb-3">
        <strong>Обнаружения:</strong>
        @if($image->detections->count())
        <ul>
            @foreach($image->detections as $det)
            <li>
                {{ $det->target }} {{ $emoji ?? '🎯' }}:
                ({{ $det->x1 }}, {{ $det->y1 }}) → ({{ $det->x2 }}, {{ $det->y2 }})
            </li>
            @endforeach
        </ul>
        @else
        <p>Нет обнаружений</p>
        @endif
    </div>

    <a href="{{ route('admin.images.edit', $image) }}" class="btn btn-primary mt-2">Редактировать</a>
    <a href="{{ route('admin.images.index') }}" class="btn btn-secondary mt-2">Назад к списку</a>
</div>
@endsection
