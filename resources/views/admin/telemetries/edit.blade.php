@extends('layouts.admin')

@section('title', 'Редактировать телеметрию')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Редактировать телеметрию #{{ $telemetry->id }}</h1>

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

    <form action="{{ route('admin.telemetries.update', $telemetry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон</label>
            <select name="drone_id" id="drone_id" class="form-select" required>
                @foreach($drones as $drone)
                <option value="{{ $drone->id }}" {{ $telemetry->drone_id == $drone->id ? 'selected' : '' }}>
                    {{ $drone->name }}
                </option>
                @endforeach
            </select>
            @error('drone_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $telemetry->latitude) }}"
                   required>
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="text" name="longitude" class="form-control"
                   value="{{ old('longitude', $telemetry->longitude) }}" required>
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (altitude)</label>
            <input type="text" name="altitude" class="form-control" value="{{ old('altitude', $telemetry->altitude) }}">
            @error('altitude') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.telemetries.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection