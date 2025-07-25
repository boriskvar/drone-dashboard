<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Bootstrap 5 -->
    @vite(['resources/css/main.css', 'resources/js/main.js'])

    @stack('styles')
</head>

<body class="bg-light">
    <div class="d-flex vh-100">
        {{-- Админский сайдбар --}}
        <div class="w-25 bg-dark text-white p-4" style="min-width: 250px;">
            @include('partials.admin_sidebar', [
            'activeRoute' => request()->route()->getName()
            ])
        </div>

        {{-- Основной контент --}}
        <main class="flex-grow-1 overflow-auto p-4">
            @hasSection('header')
            <header class="bg-white shadow-sm p-3 mb-4 rounded">
                <h1 class="h3 mb-0">@yield('header')</h1>
            </header>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>

</html>
