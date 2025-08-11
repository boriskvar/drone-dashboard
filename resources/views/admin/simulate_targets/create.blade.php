@extends('layouts.admin')

@section('title', 'Назначить цель дрону (Симуляция)')

@section('content')
<h1 class="mb-4">Назначить цель дрону</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.simulate_targets.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="drone_id" class="form-label">Дрон</label>
        <select name="drone_id" id="drone_id" class="form-select" required>
            @foreach ($drones as $drone)
            <option value="{{ $drone->id }}">
                {{ $drone->name ?? 'Дрон #' . $drone->id }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="latitude" class="form-label">Широта (latitude)</label>
        <input
               type="number"
               step="any"
               name="latitude"
               id="latitude"
               class="form-control"
               placeholder="Например: 50.4501"
               title="Широта: положительные значения — север, отрицательные — юг"
               required>
    </div>

    <div class="mb-3">
        <label for="longitude" class="form-label">Долгота (longitude)</label>
        <input
               type="number"
               step="any"
               name="longitude"
               id="longitude"
               class="form-control"
               placeholder="Например: 30.5234"
               title="Долгота: положительные значения — восток, отрицательные — запад"
               required>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a href="{{ route('admin.simulate_targets.index') }}" class="btn btn-secondary">Отмена</a>
</form>
@endsection
