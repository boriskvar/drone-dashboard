@extends('layouts.admin')

@section('title', 'Назначить цель дрону')

@section('content')
<h1>Назначить цель дрону</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.targets.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="drone_id" class="form-label">Дрон</label>
        <select name="drone_id" id="drone_id" class="form-select" required>
            @foreach ($drones as $drone)
            <option value="{{ $drone->id }}">{{ $drone->name ?? 'Дрон #' . $drone->id }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="lat" class="form-label">Широта</label>
        <input type="text" name="lat" id="lat" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="lng" class="form-label">Долгота</label>
        <input type="text" name="lng" id="lng" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
@endsection