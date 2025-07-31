<div class="sticky-top pt-3">
    <h2 class="h4 mb-4">Админ-панель</h2>

    <nav class="nav flex-column">
        @php
        $adminMenuItems = [
        ['title' => 'Дроны', 'url' => route('admin.drones.index'), 'route' => 'admin.drones.index'],
        ['title' => 'Добавить дрон', 'url' => route('admin.drones.create'), 'route' => 'admin.drones.create'],

        ['title' => 'Позиции дронов', 'url' => route('admin.positions.index'), 'route' => 'admin.positions.index'],
        ['title' => 'Добавить позицию', 'url' => route('admin.positions.create'), 'route' => 'admin.positions.create'],

        ['title' => 'Телеметрия', 'url' => route('admin.telemetries.index'), 'route' => 'admin.telemetries.index'],
        ['title' => 'Добавить телеметрию', 'url' => route('admin.telemetries.create'), 'route' =>
        'admin.telemetries.create'],

        // Новый раздел — цели
        ['title' => 'Цели', 'url' => route('admin.targets.index'), 'route' => 'admin.targets.index'],
        ['title' => 'Назначить цель', 'url' => route('admin.targets.create'), 'route' => 'admin.targets.create'],
        ];
        @endphp

        @foreach($adminMenuItems as $item)
        <a href="{{ $item['url'] }}"
           class="nav-link text-white mb-2 rounded {{ $activeRoute === $item['route'] ? 'bg-primary' : 'hover-bg-gray-700' }}">
            {{ $item['title'] }}
        </a>
        @endforeach
    </nav>
</div>