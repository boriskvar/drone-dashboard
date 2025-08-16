<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!--Подключается main.css и main.js через vite. Это твои кастомные стили и скрипты для Bootstrap 5 -->
    @vite(['resources/css/main.css', 'resources/js/main.js'])

    <!-- Bootstrap 5 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}


    <!-- Дополнительные CSS стили -->
    @stack('styles')
</head>

<body class="bg-light">
    <div class="d-flex vh-100">
        <!-- Сайдбар (вынесен в partials.sidebar и получает menuItems и activeRoute)-->
        <div class="bg-dark text-white p-4" style="min-width: 250px;">
            @include('partials.sidebar', [
            'menuItems' => $menuItems ?? [],
            'activeRoute' => request()->route()?->getName()
            ])
        </div>

        <!-- Основной контент -->
        <main class="flex-grow-1 overflow-auto p-4">
            <!-- Заголовок страницы -->
            @hasSection('header')
            <header class="bg-white shadow-sm p-3 mb-4 rounded">
                <h1 class="h3 mb-0">@yield('header')</h1>
            </header>
            @endif

            <!-- Основное содержимое -->
            @yield('content')
        </main>
    </div>

    <!-- Общие скрипты Blade -->
    @yield('scripts')
    <!-- Скрипты -->
    @stack('scripts')

    <!-- Bootstrap 5 JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>

</html>
