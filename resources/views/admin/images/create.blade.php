@extends('layouts.admin')

@section('title', 'Загрузка новой картинки')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Загрузка новой картинки</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data"
          class="d-flex gap-2 flex-column">
        @csrf
        <div class="mb-3">
            <label>Файл изображения</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Название (необязательно)</label>
            <input type="text" name="title" class="form-control">
        </div>
        <button class="btn btn-primary">Загрузить</button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary mt-2">Назад к списку</a>
    </form>
</div>
@endsection
