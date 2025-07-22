@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div id="app" class="container py-4">
    <h1>Карта дронов</h1>
    <drone-map
        :initial-drones='@json($drones)'>{{-- данные, передаваемые из контроллера --}}
        ></drone-map>
</div>
@endsection
