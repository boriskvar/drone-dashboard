@extends('layouts.admin')

@section('title', 'Редактирование картинки')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Редактирование картинки #{{ $image->id }}</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.images.update', $image) }}" method="POST" enctype="multipart/form-data"
          class="d-flex gap-2 flex-column">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Текущее изображение</label>
            <div><img src="{{ asset('storage/'.$image->path) }}" width="200" alt="image"></div>
        </div>
        <div class="mb-3">
            <label>Заменить файл</label>
            <input type="file" name="file" class="form-control">
        </div>
        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="title" class="form-control" value="{{ $image->title }}">
        </div>
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary mt-2">Отмена</a>
    </form>
</div>
@endsection
