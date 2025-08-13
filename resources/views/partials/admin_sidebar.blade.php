<div class="sticky-top pt-3">
    <h2 class="h4 mb-4">Админ-панель</h2>

    <nav class="nav flex-column">
        @php
        $adminMenuItems = [
        ['title' => 'Дроны', 'url' => route('admin.drones.index'), 'route' => 'admin.drones.index'],
        ['title' => 'Добавить дрон', 'url' => route('admin.drones.create'), 'route' => 'admin.drones.create'],

        // Симуляция данных полета
        ['title' => 'Симуляция данных полёта', 'url' => route('admin.simulate_flight_data.index'), 'route' =>
        'admin.simulate_flight_data.index'],
        ['title' => 'Добавить данные полёта', 'url' => route('admin.simulate_flight_data.create'), 'route' =>
        'admin.simulate_flight_data.create'],

        // Симуляция целей
        ['title' => 'Симуляция Цели', 'url' => route('admin.simulate_targets.index'), 'route' =>
        'admin.simulate_targets.index'],
        ['title' => 'Добавить цель', 'url' => route('admin.simulate_targets.create'), 'route' =>
        'admin.simulate_targets.create'],
        ];

        @endphp

        @foreach($adminMenuItems as $item)
        <a href="{{ $item['url'] }}" class="nav-link text-white mb-2 rounded {{ $activeRoute === $item['route'] ? 'bg-primary' : 'hover-bg-gray-700' }}">
            {{ $item['title'] }}
        </a>
        @endforeach
    </nav>
</div>