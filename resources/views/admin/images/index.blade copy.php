@extends('layouts.admin')

@section('content')
<h1>Обработка изображений</h1>

<form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <button class="btn btn-primary">Загрузить</button>
</form>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Изображение</th>
            <th>Обнаружения</th>
        </tr>
    </thead>
    <tbody>
        @foreach($images as $img)
        <tr>
            <td>{{ $img->id }}</td>
            <td><img src="{{ asset('storage/'.$img->path) }}" width="150"></td>
            <td>
                @foreach($img->detections as $det)
                <div>{{ $det->label }}: ({{ $det->x1 }},{{ $det->y1 }}) → ({{ $det->x2 }},{{ $det->y2 }})</div>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
