@extends('layouts.app')

@section('styles')
@vite(['resources/css/bootstrap.css'])
@endsection

@section('content')
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Статистика</a>
        </li>
    </ul>

    <div class="mt-3">
        <!-- Bootstrap-таблица -->
        <table class="table table-striped">
            ...
        </table>
    </div>
</div>
@endsection
