@extends('layouts.admin')

@section('title', 'Добавить данные полёта')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Добавить данные полёта</h1>

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

    <form action="{{ route('admin.simulate_flight_data.store') }}" method="POST">
        @csrf

        {{-- Выбор дрона --}}
        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон</label>
            <select name="drone_id" id="drone_id" class="form-select" required>
                <option value="">-- выберите дрон --</option>
                @foreach($drones as $drone)
                <option value="{{ $drone->id }}" {{ old('drone_id') == $drone->id ? 'selected' : '' }}>
                    {{ $drone->name }}
                </option>
                @endforeach
            </select>
            <div class="form-text">Выберите дрон, для которого будут симулироваться данные полёта.</div>
            @error('drone_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Координаты --}}
        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="number" name="latitude" id="latitude" step="0.0000001" class="form-control"
                   value="{{ old('latitude') }}" required>
            <div class="form-text">Например: <code>50.4501</code></div>
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="number" name="longitude" id="longitude" step="0.0000001" class="form-control"
                   value="{{ old('longitude') }}" required>
            <div class="form-text">Например: <code>30.5234</code></div>
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Высота --}}
        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (м)</label>
            <input type="number" name="altitude" id="altitude" step="0.1" class="form-control"
                   value="{{ old('altitude') }}">
            <div class="form-text">Введите высоту полёта в метрах, например: <code>120.5</code></div>
            @error('altitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Скорость --}}
        <div class="mb-3">
            <label for="speed" class="form-label">Скорость (км/ч)</label>
            <input type="number" name="speed" id="speed" step="0.1" class="form-control"
                   value="{{ old('speed') }}">
            <div class="form-text">Введите скорость дрона, например: <code>15.3</code></div>
            @error('speed') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Курс --}}
        <div class="mb-3">
            <label for="heading" class="form-label">Курс (°)</label>
            <input type="number" name="heading" id="heading" step="0.1" class="form-control"
                   value="{{ old('heading') }}">
            <div class="form-text">Введите направление полёта от 0° до 360°, например: <code>270</code></div>
            @error('heading') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.simulate_flight_data.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
