@extends('layouts.main')

@section('title', 'Админ-панель')

@section('styles')
@vite('resources/css/main.css')
@endsection

@section('content')
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Статистика</a>
        </li>
    </ul>

    <div class="mt-3">
        <table class="table table-striped">
            <!-- ... -->
        </table>
    </div>

    <!-- Vue-компонент -->
    <div id="app" class="mt-4">
        <example-component></example-component>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/main.js')
@endsection
