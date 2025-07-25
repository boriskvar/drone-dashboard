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
            <label for="lat" class="form-label">Широта</label>
            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror"
                value="{{ old('lat', $drone->lat) }}">
            @error('lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="lng" class="form-label">Долгота</label>
            <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror"
                value="{{ old('lng', $drone->lng) }}">
            @error('lng') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
