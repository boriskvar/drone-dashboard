@extends('layouts.admin')

@section('title', 'Добавить координаты')

@section('header', 'Добавить координаты дрона')

@section('content')
<div class="container py-4">
    <h1>➕ Добавить координаты</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ошибка:</strong> Проверьте заполнение полей.
        <ul>
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.positions.store') }}">
        @csrf

        <div class="mb-3">
            <label for="drone_id" class="form-label">Дрон</label>
            <select name="drone_id" class="form-select @error('drone_id') is-invalid @enderror">
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
            <label for="lat" class="form-label">Широта</label>
            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror"
                   value="{{ old('lat') }}">
            @error('lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Введите широту (например, 50.4501)</small>
        </div>

        <div class="mb-3">
            <label for="lng" class="form-label">Долгота</label>
            <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror"
                   value="{{ old('lng') }}">
            @error('lng') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-danger">Введите долготу (например, 30.5234)</small>
        </div>

        <button type="submit" class="btn btn-success">💾 Сохранить</button>
        <a href="{{ url('/positions') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
