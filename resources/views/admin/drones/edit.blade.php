@extends('layouts.admin')

@section('title', 'Редактировать дрон')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Редактировать дрон #{{ $drone->id }}</h1>

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

    <form action="{{ route('admin.drones.update', $drone->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Имя дрона</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $drone->name) }}" required>
        </div>


        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select" required>
                <option value="">-- выберите статус --</option>
                @foreach(\App\Models\Drone::statuses() as $value => $label)
                <option value="{{ $value }}" {{ old('status', $drone->status ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            @error('status') <div class="text-danger">{{ $message }}</div> @enderror
        </div>


        <div class="mb-3">
            <label for="latitude" class="form-label">Широта (latitude)</label>
            <input type="text" name="latitude" id="latitude" step="0.0000001" class="form-control"
                   value="{{ old('latitude', $drone->latitude) }}">
            @error('latitude') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Например: 50.4501 (северное значение положительное).</small>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота (longitude)</label>
            <input type="text" name="longitude" id="longitude" step="0.0000001" class="form-control"
                   value="{{ old('longitude', $drone->longitude) }}">
            @error('longitude') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Например: 30.5234 (восточное значение положительное).</small>
        </div>

        <!-- <div class="mb-3">
            <label for="model" class="form-label">Модель</label>
            <input type="text" name="model" id="model" class="form-control"
                   value="{{ old('model', $drone->model) }}">
            <small class="form-text text-danger">Например: "DJI Mavic 3" или "Autel EVO II".</small>
        </div> -->

        <!-- <div class="mb-3">
            <label for="serial_number" class="form-label">Серийный номер</label>
            <input type="text" name="serial_number" id="serial_number" class="form-control"
                   value="{{ old('serial_number', $drone->serial_number) }}">
            <small class="form-text text-danger">Для учёта и идентификации дрона.</small>
        </div> -->

        <!-- <div class="mb-3">
            <label for="manufacturer" class="form-label">Производитель</label>
            <input type="text" name="manufacturer" id="manufacturer" class="form-control"
                   value="{{ old('manufacturer', $drone->manufacturer) }}">
            <small class="form-text text-danger">Например: "DJI", "Autel", "Baykar".</small>
        </div> -->

        <!-- <div class="mb-3">
            <label for="manufacture_date" class="form-label">Дата производства</label>
            <input type="date" name="manufacture_date" id="manufacture_date" class="form-control"
                   value="{{ old('manufacture_date', $drone->manufacture_date ? $drone->manufacture_date->format('Y-m-d') : '') }}">
            <small class="form-text text-danger">Дата выпуска дрона.</small>
        </div> -->


        <!-- <div class="mb-3">
            <label for="firmware_version" class="form-label">Версия прошивки</label>
            <input type="text" name="firmware_version" id="firmware_version" class="form-control"
                   value="{{ old('firmware_version', $drone->firmware_version) }}">
            <small class="form-text text-danger">Например: "v01.02.0300".</small>
        </div> -->

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.drones.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
