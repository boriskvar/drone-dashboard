@extends('layouts.main')

@section('title', 'Панель оператора')

@section('header', 'Панель оператора')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title">Vue-компонент:Текущие задания</h3>
            <!-- Контент страницы -->
            <!-- Vue-приложение -->
            <div id="app">
                <example-component></example-component>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Скрипты для этой страницы
    console.log('Main layout loaded');
</script>
@endsection
