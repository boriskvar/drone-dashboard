@extends('layouts.admin')

@section('title', 'Редактировать данные полёта')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Редактировать данные полёта #{{ $flight->id }}</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Ошибка!</strong> Проверьте введённые данные.
        <ul class="mb-0">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.flight_data.update', $flight->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Дрон --}}
        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон <span class="text-danger">*</span></label>
            <select name="drone_id" id="drone_id" class="form-select" required>
                <option value="">-- выберите дрон --</option>
                @foreach($drones as $drone)
                <option value="{{ $drone->id }}" {{ old('drone_id', $flight->drone_id) == $drone->id ? 'selected' : '' }}>
                    {{ $drone->name }}
                </option>
                @endforeach
            </select>
            @error('drone_id') <div class="text-danger">{{ $message }}</div> @enderror
            <div class="form-text">Выберите дрон, для которого редактируются данные полёта.</div>
        </div>

        {{-- Широта --}}
        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude) <span class="text-danger">*</span></label>
            <input type="text" name="latitude" id="latitude" step="0.0000001" class="form-control" value="{{ old('latitude', $flight->latitude) }}" required>
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
            <div class="form-text">Введите широту в формате десятичных градусов, например: <code>50.4501</code></div>
        </div>

        {{-- Долгота --}}
        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude) <span class="text-danger">*</span></label>
            <input type="text" name="longitude" id="longitude" step="0.0000001" class="form-control" value="{{ old('longitude', $flight->longitude) }}" required>
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
            <div class="form-text">Введите долготу в формате десятичных градусов, например: <code>30.5234</code></div>
        </div>

        {{-- Высота --}}
        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (м)</label>
            <input type="number" name="altitude" id="altitude" step="0.1" class="form-control" value="{{ old('altitude', $flight->altitude) }}">
            <div class="form-text">Высота полёта в метрах. Пример: <code>120.5</code></div>
        </div>

        {{-- Скорость --}}
        <div class="mb-3">
            <label for="speed" class="form-label">Скорость (км/ч)</label>
            <input type="number" name="speed" id="speed" step="0.1" class="form-control" value="{{ old('speed', $flight->speed) }}">
            <div class="form-text">Скорость дрона в километрах в час. Пример: <code>15.3</code></div>
        </div>

        {{-- Курс --}}
        <div class="mb-3">
            <label for="heading" class="form-label">Курс (°)</label>
            <input type="number" name="heading" id="heading" step="0.1" class="form-control" value="{{ old('heading', $flight->heading) }}">
            <div class="form-text">Направление движения в градусах от <code>0</code> до <code>360</code>.</div>
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.flight_data.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection