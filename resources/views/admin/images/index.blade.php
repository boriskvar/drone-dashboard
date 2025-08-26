@extends('layouts.admin')

@section('title', 'Обработка изображений')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Обработка изображений</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Форма загрузки новой картинки -->
    <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data"
          class="mb-3 d-flex gap-2 align-items-center">
        @csrf
        <input type="file" name="file" class="form-control" required>
        <input type="text" name="title" class="form-control" placeholder="Название (необязательно)">
        <button class="btn btn-primary">Загрузить</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Обнаружения</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $img)
            <tr>
                <td>{{ $img->id }}</td>
                <td><img src="{{ asset('storage/'.$img->path) }}" width="150" alt="image"></td>
                <td>{{ $img->title }}</td>
                <td>
                    @foreach($img->detections as $det)
                    <div>{{ $det->target }}: ({{ $det->x1 }},{{ $det->y1 }}) → ({{ $det->x2 }},{{ $det->y2 }})</div>
                    @endforeach
                </td>
                <td class="d-flex flex-wrap gap-1">
                    <!-- Редактировать -->
                    <a href="{{ route('admin.images.edit', $img) }}" class="btn btn-sm btn-primary"
                       title="Редактировать">✏️</a>

                    <!-- Удалить -->
                    <form action="{{ route('admin.images.destroy', $img) }}" method="POST"
                          onsubmit="return confirm('Удалить изображение?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Удалить">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Пагинация -->
    {{ $images->links() }}
</div>
@endsection
