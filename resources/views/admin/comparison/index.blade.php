@extends('layouts.admin')

@section('title', 'Сравнение картинок')

@section('content')
<div class="container mt-4">
    <h2>Сравнение картинок</h2>

    <form action="{{ route('admin.comparison.compare') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label>Картинка 1</label>
                <select name="image1_id" class="form-select" required>
                    @foreach($images as $img)
                    <option value="{{ $img->id }}">#{{ $img->id }} — {{ $img->title ?? $img->path }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Картинка 2</label>
                <select name="image2_id" class="form-select" required>
                    @foreach($images as $img)
                    <option value="{{ $img->id }}">#{{ $img->id }} — {{ $img->title ?? $img->path }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Сравнить</button>
    </form>
</div>
@endsection
