@extends('layouts.admin')

@section('title', 'Редактировать обнаружение')

@section('content')
<div class="container mt-4">
    <h2>Редактировать обнаружение</h2>



    <form action="{{ route('admin.detections.update', $detection) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="image_id" class="form-label">Картинка</label>
            <select name="image_id" id="image_id" class="form-select" required>
                @foreach($images as $img)
                <option value="{{ $img->id }}" {{ $detection->image_id == $img->id ? 'selected' : '' }}>
                    {{ $img->id }} — {{ $img->path }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="target" class="form-label">Цель</label>
            <input type="text" name="target" id="target" class="form-control" value="{{ $detection->target }}" required>
        </div>

        <div class="mb-3">
            <p class="text-muted">
                Координаты рамки цели на изображении:<br>
                <strong>(x1, y1)</strong> — верхний левый угол,
                <strong>(x2, y2)</strong> — нижний правый угол.
            </p>

            <div class="row">
                <div class="col">
                    <label for="x1">x1</label>
                    <input type="number" step="any" name="x1" id="x1" class="form-control" value="{{ $detection->x1 }}"
                           required>
                </div>
                <div class="col">
                    <label for="y1">y1</label>
                    <input type="number" step="any" name="y1" id="y1" class="form-control" value="{{ $detection->y1 }}"
                           required>
                </div>
                <div class="col">
                    <label for="x2">x2</label>
                    <input type="number" step="any" name="x2" id="x2" class="form-control" value="{{ $detection->x2 }}"
                           required>
                </div>
                <div class="col">
                    <label for="y2">y2</label>
                    <input type="number" step="any" name="y2" id="y2" class="form-control" value="{{ $detection->y2 }}"
                           required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="{{ route('admin.detections.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
