@extends('layouts.admin')

@section('content')
  <h2>Просмотр обнаружения #{{ $detection->id }}</h2>

  <div class="card mb-3">
    <div class="card-body">

      @if ($detection->image && $detection->image->path)
        <div style="position: relative; display: inline-block;">
          @php
            // Размер отображения
            $displayWidth = 400;
            $displayHeight = round(($displayWidth * $detection->image->height) / $detection->image->width);

            // Пересчёт из нормализованных координат в пиксели
            $x1 = $detection->x1 * $displayWidth;
            $y1 = $detection->y1 * $displayHeight;
            $width = ($detection->x2 - $detection->x1) * $displayWidth;
            $height = ($detection->y2 - $detection->y1) * $displayHeight;
          @endphp

          <img src="{{ asset('storage/' . $detection->image->path) }}"
               width="{{ $displayWidth }}"
               height="{{ $displayHeight }}"
               alt="image">

          <div
               style="
        position: absolute;
        top: {{ $y1 }}px;
        left: {{ $x1 }}px;
        width: {{ $width }}px;
        height: {{ $height }}px;
        border: 2px solid red;
        background: rgba(255,0,0,0.2);
    ">
            <span
                  style="
            position: absolute;
            top: -18px;
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
      @else
        — картинки нет —
      @endif

      <p class="mt-3"><strong>Цель:</strong> {{ $detection->target }}</p>
      <p><strong>Координаты:</strong>
        <span id="coords">
          ({{ $detection->x1 }}, {{ $detection->y1 }}) — ({{ $detection->x2 }}, {{ $detection->y2 }})
        </span>
      </p>
    </div>
  </div>

  <a href="{{ route('admin.detections.edit', $detection) }}" class="btn btn-warning">Редактировать</a>
  <a href="{{ route('admin.detections.index') }}" class="btn btn-secondary">Назад</a>

  {{-- JS для выделения рамки --}}
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const canvas = document.getElementById("canvas");
      const ctx = canvas.getContext("2d");

      let startX, startY, isDrawing = false;

      canvas.addEventListener("mousedown", (e) => {
        const rect = canvas.getBoundingClientRect();
        startX = e.clientX - rect.left;
        startY = e.clientY - rect.top;
        isDrawing = true;
      });

      canvas.addEventListener("mousemove", (e) => {
        if (!isDrawing) return;

        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // очистить холст и нарисовать прямоугольник
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = "red";
        ctx.lineWidth = 2;
        ctx.strokeRect(startX, startY, x - startX, y - startY);
      });

      canvas.addEventListener("mouseup", (e) => {
        isDrawing = false;
        const rect = canvas.getBoundingClientRect();
        const endX = e.clientX - rect.left;
        const endY = e.clientY - rect.top;

        document.getElementById("coords").innerText =
          `(${startX.toFixed(2)}, ${startY.toFixed(2)}) — (${endX.toFixed(2)}, ${endY.toFixed(2)})`;
      });
    });
  </script>
@endsection
