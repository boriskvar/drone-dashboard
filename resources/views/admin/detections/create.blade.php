@extends('layouts.admin')

@section('content')
  <h2>Добавить обнаружение</h2>

  <form method="POST" action="{{ route('admin.detections.store') }}">
    @csrf

    {{-- 1️⃣ Выпадающий список картинок --}}
    <div class="mb-3">
      <label for="image_id" class="form-label">Картинка</label>
      <select name="image_id"
              id="image_id"
              class="form-select"
              required>
        <option value="">— выберите картинку —</option>
        @foreach ($images as $img)
          <option value="{{ $img->id }}" data-path="{{ asset('storage/' . $img->path) }}">
            #{{ $img->id }} — {{ $img->title ?? $img->path }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- 2️⃣ Поле для цели --}}
    <div class="mb-3">
      <label for="target" class="form-label">Цель</label>
      <input type="text"
             name="target"
             id="target"
             class="form-control"
             required>
    </div>

    {{-- 3️⃣ Картинка + Canvas --}}
    <div class="mb-3" style="position: relative; display: inline-block;">
      <img id="preview-img"
           src=""
           alt="preview"
           style="max-width: 100%; display: none;">

      <canvas id="canvas" style="position: absolute; top: 0; left: 0; display: none; border:1px solid #ccc;">
      </canvas>
    </div>

    {{-- 4️⃣ Скрытые поля для координат --}}
    <input type="hidden"
           name="x1"
           id="x1">
    <input type="hidden"
           name="y1"
           id="y1">
    <input type="hidden"
           name="x2"
           id="x2">
    <input type="hidden"
           name="y2"
           id="y2">

    {{-- 5️⃣ Кнопка отправки --}}
    <button type="submit" class="btn btn-primary mt-2">Сохранить</button>
    <button type="button"
            id="reset-btn"
            class="btn btn-secondary mt-2">Отмена</button>

  </form>
@endsection

@section('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const select = document.getElementById("image_id");
      const preview = document.getElementById("preview-img");
      const canvas = document.getElementById("canvas");
      const ctx = canvas.getContext("2d");

      const x1 = document.getElementById("x1");
      const y1 = document.getElementById("y1");
      const x2 = document.getElementById("x2");
      const y2 = document.getElementById("y2");

      /* 👉 Когда выбираем картинку */
      select.addEventListener("change", () => {
        const option = select.options[select.selectedIndex];
        const url = option.dataset.path;

        if (url) {
          preview.src = url;
          preview.style.display = "block";

          preview.onload = () => {
            canvas.width = preview.width;
            canvas.height = preview.height;
            canvas.style.display = "block";
            ctx.clearRect(0, 0, canvas.width, canvas.height);
          };
        }
      });

      /* 👉 Рисование рамки */
      let drawing = false;
      let startX, startY;

      canvas.addEventListener("mousedown", (e) => {
        drawing = true;
        const rect = canvas.getBoundingClientRect();
        startX = e.clientX - rect.left;
        startY = e.clientY - rect.top;
      });

      //`mousemove` — пока рисуем
      canvas.addEventListener("mousemove", (e) => {
        if (!drawing) return;
        const rect = canvas.getBoundingClientRect();
        const currentX = e.clientX - rect.left;
        const currentY = e.clientY - rect.top;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = "red";
        ctx.lineWidth = 2;
        ctx.strokeRect(startX, startY, currentX - startX, currentY - startY);

        // 🔹 Полупрозрачная заливка внутри рамки
        ctx.fillStyle = "rgba(255, 0, 0, 0.2)";
        ctx.fillRect(startX, startY, currentX - startX, currentY - startY);
      });

      // `mouseup` — фиксируем рамку
      canvas.addEventListener("mouseup", (e) => {
        drawing = false;
        const rect = canvas.getBoundingClientRect();
        const endX = e.clientX - rect.left;
        const endY = e.clientY - rect.top;

        // Записываем координаты в hidden inputs
        x1.value = Math.round(startX);
        y1.value = Math.round(startY);
        x2.value = Math.round(endX);
        y2.value = Math.round(endY);

        // 👉 оставляем рамку + заливку после отпускания мыши
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = "red";
        ctx.lineWidth = 2;
        ctx.strokeRect(startX, startY, endX - startX, endY - startY);

        ctx.fillStyle = "rgba(255, 0, 0, 0.2)"; // красная заливка с прозрачностью
        ctx.fillRect(startX, startY, endX - startX, endY - startY);
      });

      // Кнопка "Отмена" — сброс
      document.getElementById("reset-btn").addEventListener("click", () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // очищаем координаты
        x1.value = "";
        y1.value = "";
        x2.value = "";
        y2.value = "";
      });

    });
  </script>
@endsection
