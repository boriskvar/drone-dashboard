@extends('layouts.admin')

@section('title', 'Добавить телеметрию')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Добавить телеметрию</h1>

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

    <form action="{{ route('admin.telemetries.store') }}" method="POST">
        @csrf

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
            @error('drone_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}" required>
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Введите широту (например - Киев, 50.4501)</small>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}" required>
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Введите долготу (например - Киев, 30.5234)</small>
        </div>

        <div class="mb-3">
            <label for="altitude" class="form-label">Высота (altitude)</label>
            <input type="text" name="altitude" class="form-control" value="{{ old('altitude') }}">
            @error('altitude') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Введите высоту в метрах (например, 120.5)</small>
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.telemetries.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
