<div class="sticky-top pt-3">
    <h2 class="h4 mb-4">Админ-панель</h2>

    <nav class="nav flex-column">
        @php
        $adminMenuItems = [
        ['title' => 'Дроны', 'url' => route('admin.drones.index'), 'route' => 'admin.drones.index'],
        ['title' => 'Добавить дрон', 'url' => route('admin.drones.create'), 'route' => 'admin.drones.create'],
        // Можно добавлять другие пункты
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
