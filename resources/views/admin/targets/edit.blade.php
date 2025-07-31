@extends('layouts.admin')

@section('content')
<h2>Редактирование цели</h2>

<form method="POST" action="{{ route('admin.targets.update', $target->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Дрон</label>
        <select name="drone_id" class="form-control">
            @foreach($drones as $drone)
            <option value="{{ $drone->id }}" @selected($drone->id == $target->drone_id)>
                #{{ $drone->id }} {{ $drone->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Широта</label>
        <input type="text" name="lat" value="{{ old('lat', $target->lat) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Долгота</label>
        <input type="text" name="lng" value="{{ old('lng', $target->lng) }}" class="form-control">
    </div>

    <button class="btn btn-success">Сохранить</button>
    <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">Отмена</a>
</form>
@endsection