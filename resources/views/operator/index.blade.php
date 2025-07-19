@extends('layouts.app')

{{-- Подключаем ТОЛЬКО Bootstrap для этой страницы --}}
@section('styles')
@vite(['resources/css/bootstrap.css'])
@endsection

@section('header')
<h1 class="text-2xl font-bold">Панель оператора</h1>
@endsection

@section('content')
<!-- Используем Bootstrap-классы -->
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Управление дронами
        </div>
        <div class="card-body">
            <p>Контент панели оператора...</p>
        </div>
    </div>
</div>
@endsection
