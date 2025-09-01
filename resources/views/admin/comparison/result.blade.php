@extends('layouts.admin')

@section('title', 'Результат сравнения')

@section('content')
<div class="container mt-4">
    <h2>Результат сравнения</h2>

    <div class="row">
        <div class="col">
            <h4>Картинка 1 (#{{ $image1->id }})</h4>
            <div style="position: relative; display: inline-block;">
                <img src="{{ asset('storage/'.$image1->path) }}" width="400">
                @foreach($image1->detections as $det)
                @php
                $isMatch = collect($results['matches'])->contains(fn($pair) => $pair[0]->id === $det->id);
                @endphp
                <div style="
                        position: absolute;
                        top: {{ $det->y1 }}px;
                        left: {{ $det->x1 }}px;
                        width: {{ $det->x2 - $det->x1 }}px;
                        height: {{ $det->y2 - $det->y1 }}px;
                        border: 2px solid {{ $isMatch ? 'green' : 'red' }};
                        background: rgba({{ $isMatch ? '0,255,0,0.2' : '255,0,0,0.2' }});
                    ">
                    <span style="
                            position: absolute;
                            top: -18px;
                            left: 0;
                            background: {{ $isMatch ? 'green' : 'red' }};
                            color: white;
                            font-size: 12px;
                            padding: 2px 4px;
                        ">
                        {{ $det->target }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col">
            <h4>Картинка 2 (#{{ $image2->id }})</h4>
            <div style="position: relative; display: inline-block;">
                <img src="{{ asset('storage/'.$image2->path) }}" width="400">
                @foreach($image2->detections as $det)
                @php
                $isMatch = collect($results['matches'])->contains(fn($pair) => $pair[1]->id === $det->id);
                @endphp
                <div style="
                        position: absolute;
                        top: {{ $det->y1 }}px;
                        left: {{ $det->x1 }}px;
                        width: {{ $det->x2 - $det->x1 }}px;
                        height: {{ $det->y2 - $det->y1 }}px;
                        border: 2px solid {{ $isMatch ? 'green' : 'blue' }};
                        background: rgba({{ $isMatch ? '0,255,0,0.2' : '0,0,255,0.2' }});
                    ">
                    <span style="
                            position: absolute;
                            top: -18px;
                            left: 0;
                            background: {{ $isMatch ? 'green' : 'blue' }};
                            color: white;
                            font-size: 12px;
                            padding: 2px 4px;
                        ">
                        {{ $det->target }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr>

    <h4>Совпадения ✅ (зелёные рамки)</h4>
    <ul>
        @forelse($results['matches'] as [$det1, $det2])
        <li>{{ $det1->target }}</li>
        @empty
        <li>Нет совпадений</li>
        @endforelse
    </ul>

    <h4>Только в первой картинке ❌ (красные рамки)</h4>
    <ul>
        @forelse($results['only_in_first'] as $det)
        <li>{{ $det->target }} ({{ $det->x1 }}, {{ $det->y1 }})</li>
        @empty
        <li>—</li>
        @endforelse
    </ul>

    <h4>Только во второй картинке ❌ (синие рамки)</h4>
    <ul>
        @forelse($results['only_in_second'] as $det)
        <li>{{ $det->target }} ({{ $det->x1 }}, {{ $det->y1 }})</li>
        @empty
        <li>—</li>
        @endforelse
    </ul>

    <a href="{{ route('admin.comparison.index') }}" class="btn btn-secondary mt-3">Назад</a>
</div>
@endsection
