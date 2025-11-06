<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Figtree', sans-serif;
            background-color: var(--fallback-b2, #f5f6fa);
        }

        .layout {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .sidebar-container {
            width: 260px;
            background-color: var(--fallback-b1, #fff);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            height: 100vh;
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        /* Default main wrapper (no sidebar) */
        .main-wrapper {
            margin-left: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            transition: all 0.3s ease;
        }

        /* When sidebar is visible (admin) */
        .has-sidebar .main-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: var(--fallback-b2, #f9fafb);
        }

        header {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="layout
        @auth
            @if(auth()->user()->isAdmin()) has-sidebar @endif
        @endauth
    ">
        @auth
            @if(auth()->user()->isAdmin())
                <aside class="sidebar-container hidden md:block">
                    @include('layouts.sidebar')
                </aside>
            @endif
        @endauth

        <div class="main-wrapper">
            <div class="sticky top-0 z-50 bg-white border-b shadow-sm">
                @include('layouts.navigation')
            </div>

            <div class="main-content">
                @isset($header)
                    <header class="bg-base-100 shadow p-4 rounded-lg">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
