@extends('layouts.admin')

@section('title', 'Редактировать цель')

@section('content')
<h1>Редактировать цель</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.targets.update', $target->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="drone_id" class="form-label">Дрон</label>
        <select name="drone_id" id="drone_id" class="form-select" required>
            @foreach($drones as $drone)
            <option value="{{ $drone->id }}" @selected($drone->id == old('drone_id', $target->drone_id))>
                #{{ $drone->id }} {{ $drone->name ?? 'Дрон #' . $drone->id }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="latitude" class="form-label">Широта</label>
        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $target->latitude) }}" class="form-control" placeholder="Напр., 50.4500" required>
    </div>

    <div class="mb-3">
        <label for="longitude" class="form-label">Долгота</label>
        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $target->longitude) }}" class="form-control" placeholder="Напр., 30.5233" required>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">Отмена</a>
</form>
@endsection