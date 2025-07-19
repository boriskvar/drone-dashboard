<div class="sticky-top pt-3">
    <h2 class="h4 mb-4">{{ config('app.name') }}</h2>
    <nav class="nav flex-column">
        @foreach($menuItems as $item)
        <a href="{{ $item['url'] }}"
            class="nav-link text-white mb-2 rounded {{ $activeRoute === $item['route'] ? 'bg-primary' : 'hover-bg-gray-700' }}">
            {{ $item['title'] }}
        </a>
        @endforeach
    </nav>
</div>
