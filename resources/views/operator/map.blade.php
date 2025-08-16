@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div id="app" class="container py-4">
    <h1>Карта дрона</h1>

    {{-- Включаем Vue-компонент --}}
    <example-component></example-component>
    {{-- Если позже будем использовать DroneMap --}}
    <drone-map :initial-drones='@json($drones)'></drone-map>
</div>
@endsection
