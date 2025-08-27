@extends('layouts.admin')

@section('content')
<h2>Просмотр обнаружения #{{ $detection->id }}</h2>

<div class="card mb-3">
    <div class="card-body">

        <p><strong>ID:</strong> {{ $detection->id }}</p>

        <p><strong>Картинка с рамкой:</strong></p>
        <div style="position: relative; display: inline-block;">
            <img src="{{ asset('storage/' . $detection->image->path) }}" width="400" alt="image">

            <div style="
                position: absolute;
                left: {{ $detection->x1 }}px;
                top: {{ $detection->y1 }}px;
                width: {{ $detection->x2 - $detection->x1 }}px;
                height: {{ $detection->y2 - $detection->y1 }}px;
                border: 2px solid red;
                box-sizing: border-box;
            ">
                <span style="
                    position: absolute;
                    top: -20px;
                    left: 0;
                    background: red;
                    color: white;
                    font-size: 12px;
                    padding: 2px 4px;
                ">
                    {{ $detection->target }}
                </span>
            </div>
        </div>


        <p><strong>Цель:</strong> {{ $detection->target }}</p>
        <p><strong>Координаты:</strong> ({{ $detection->x1 }}, {{ $detection->y1 }}) —
            ({{ $detection->x2 }}, {{ $detection->y2 }})</p>
        <p><strong>Дата:</strong> {{ $detection->created_at->format('d.m.Y H:i') }}</p>

    </div>
</div>

<a href="{{ route('admin.detections.edit', $detection) }}" class="btn btn-warning">Редактировать</a>
<a href="{{ route('admin.detections.index') }}" class="btn btn-secondary">Назад</a>
@endsection
