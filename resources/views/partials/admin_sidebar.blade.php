<div class="sticky-top pt-3">
    <h2 class="h4 mb-4">Админ-панель</h2>

    <nav class="nav flex-column">
        @php
        $adminMenuItems = [
        ['title' => 'Дроны', 'url' => route('admin.drones.index'), 'route' => 'admin.drones.index'],
        ['title' => 'Добавить дрон', 'url' => route('admin.drones.create'), 'route' => 'admin.drones.create'],

        // данные полета
        ['title' => 'Данные полёта', 'url' => route('admin.flight_data.index'), 'route' =>
        'admin.flight_data.index'],
        ['title' => 'Добавить данные полёта', 'url' => route('admin.flight_data.create'), 'route' =>
        'admin.flight_data.create'],

        // цели
        ['title' => 'Цели', 'url' => route('admin.targets.index'), 'route' =>
        'admin.targets.index'],
        ['title' => 'Добавить цель', 'url' => route('admin.targets.create'), 'route' =>
        'admin.targets.create'],
        ];

        @endphp

        @foreach($adminMenuItems as $item)
        <a href="{{ $item['url'] }}" class="nav-link text-white mb-2 rounded {{ $activeRoute === $item['route'] ? 'bg-primary' : 'hover-bg-gray-700' }}">
            {{ $item['title'] }}
        </a>
        @endforeach
    </nav>
</div>