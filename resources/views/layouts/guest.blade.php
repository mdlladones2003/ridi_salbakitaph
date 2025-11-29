<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-base-200">
        <nav class="bg-white border-b border-gray-200 shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('home') }}" class="text-xl font-semibold text-blue-700 hover:text-blue-800">
                        SalbaKitaPH
                    </a>
                </div>
                <div class="flex-none">
                    <a href="{{ route('login') }}" class="btn normal-case px-6 rounded-lg bg-blue-600 hover:bg-blue-700 text-white border-none shadow-sm">
                        Get Started
                    </a>
                </div>
            </div>
        </nav>

        <main class="min-h-screen">
            {{ $slot }}
        </main>
    </body>
</html>
