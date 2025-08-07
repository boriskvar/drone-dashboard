@extends('layouts.admin')

@section('title', 'Редактировать позицию')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Редактировать позицию #{{ $position->id }}</h1>

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

    <form action="{{ route('admin.simulate_positions.update', $position->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон</label>
            <select name="drone_id" id="drone_id" class="form-select" required>
                @foreach($drones as $drone)
                <option value="{{ $drone->id }}" {{ $position->drone_id == $drone->id ? 'selected' : '' }}>
                    {{ $drone->name }}
                </option>
                @endforeach
            </select>
            @error('drone_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="text" name="latitude" id="latitude" step="0.0000001" class="form-control"
                value="{{ old('latitude', $position->latitude) }}" required>
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="text" name="longitude" id="longitude" step="0.0000001" class="form-control"
                value="{{ old('longitude', $position->longitude) }}" required>
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (м)</label>
            <input type="number" name="altitude" id="altitude" step="0.1" class="form-control"
                value="{{ old('altitude', $position->altitude) }}">
        </div>

        <div class="mb-3">
            <label for="speed" class="form-label">Скорость (км/ч)</label>
            <input type="number" name="speed" id="speed" step="0.1" class="form-control"
                value="{{ old('speed', $position->speed) }}">
        </div>

        <div class="mb-3">
            <label for="heading" class="form-label">Курс (°)</label>
            <input type="number" name="heading" id="heading" step="0.1" class="form-control"
                value="{{ old('heading', $position->heading) }}">
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.simulate_positions.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
