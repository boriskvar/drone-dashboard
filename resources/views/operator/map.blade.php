@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div id="app" class="container py-4">
    <h1>Карта дронов</h1>
    <drone-map :initial-drones='@json($drones)'></drone-map>
    <!-- <drone-map :initial-drones="{{ Js::from($drones) }}"></drone-map> -->
    <!-- <drone-map :initial-drones="{!! json_encode($drones) !!}"></drone-map> -->
</div>
@endsection
