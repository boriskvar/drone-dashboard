@extends('layouts.admin')

@section('title', 'Редактировать дрон')

@section('header', 'Редактировать дрон')

@section('content')
<div class="container py-4">
    <h1>✏️ Редактировать дрон</h1>

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

    <form method="POST" action="{{ url('/admin/drones/' . $drone->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Имя дрона</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $drone->name) }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Широта</label>
            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                value="{{ old('latitude', $drone->latitude) }}">
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Долгота</label>
            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                value="{{ old('longitude', $drone->longitude) }}">
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
                <option value="active" {{ old('status', $drone->status) == 'active' ? 'selected' : '' }}>Активен</option>
                <option value="offline" {{ old('status', $drone->status) == 'offline' ? 'selected' : '' }}>Отключен</option>

                {{-- Будут добавлены позже --}}
                {{-- <option value="idle" {{ old('status', $drone->status) == 'idle' ? 'selected' : '' }}>Ожидает</option> --}}
                {{-- <option value="in_mission" {{ old('status', $drone->status) == 'in_mission' ? 'selected' : '' }}>В миссии</option> --}}
                {{-- <option value="maintenance" {{ old('status', $drone->status) == 'maintenance' ? 'selected' : '' }}>Обслуживание</option> --}}
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>


        <button type="submit" class="btn btn-primary">💾 Обновить</button>
        <a href="{{ url('/admin/drones') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
