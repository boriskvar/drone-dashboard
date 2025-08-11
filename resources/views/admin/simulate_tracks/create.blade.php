@extends('layouts.admin')

@section('title', 'Добавить координаты трека')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">➕ Добавить координаты трека дрона</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ошибка:</strong> Проверьте заполнение полей.
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.simulate_tracks.store') }}">
        @csrf

        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон</label>
            <select name="drone_id" id="drone_id" class="form-select @error('drone_id') is-invalid @enderror" required>
                <option value="">-- Выберите дрон --</option>
                @foreach ($drones as $drone)
                <option value="{{ $drone->id }}" {{ old('drone_id') == $drone->id ? 'selected' : '' }}>
                    {{ $drone->name }} (ID: {{ $drone->id }})
                </option>
                @endforeach
            </select>
            @error('drone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="text" name="latitude" id="latitude" step="0.0001"
                   class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" required>
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Введите широту (например, 50.4501)</small>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="text" name="longitude" id="longitude" step="0.0001"
                   class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}"
                   required>
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Введите долготу (например, 30.5234)</small>
        </div>

        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (м)</label>
            <input type="number" name="altitude" id="altitude" step="0.01"
                   class="form-control @error('altitude') is-invalid @enderror" value="{{ old('altitude') }}">
            @error('altitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Введите высоту (например, 120.50)</small>
        </div>

        <div class="mb-3">
            <label for="speed" class="form-label">Скорость (км/ч)</label>
            <input type="number" name="speed" id="speed" step="0.01"
                   class="form-control @error('speed') is-invalid @enderror" value="{{ old('speed') }}">
            @error('speed') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Введите скорость (например, 15.30)</small>
        </div>

        <div class="mb-3">
            <label for="heading" class="form-label">Курс (°)</label>
            <input type="number" name="heading" id="heading" step="0.1"
                   class="form-control @error('heading') is-invalid @enderror" value="{{ old('heading') }}">
            @error('heading') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Введите курс (направление от 0 до 360°)</small>
        </div>

        <button type="submit" class="btn btn-success">💾 Сохранить</button>
        <a href="{{ route('admin.simulate_tracks.index') }}" class="btn btn-secondary ms-2">Отмена</a>
    </form>
</div>
@endsection
