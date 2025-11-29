<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalbaKitaPH — The Disaster App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Figtree font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1.2s ease forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .image-tilt {
            transition: transform 0.5s ease, box-shadow 0.3s ease;
        }

        .image-tilt:hover {
            transform: scale(1.05) rotate(-1deg);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        .card {
            backdrop-filter: blur(6px);
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
    </style>
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

    <main>
        <section class="min-h-screen max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 items-center justify-center px-8 gap-12 fade-in relative overflow-hidden">
            <div class="text-center lg:text-left space-y-6 z-10">
                <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-800 leading-tight">
                    Stay Alert. Stay Safe.
                    <span class="text-blue-700">Together.</span>
                </h1>
                <p class="text-gray-600 text-lg leading-relaxed max-w-md mx-auto lg:mx-0">
                    Report disasters, get real-time alerts, and protect your community with
                    <span class="font-semibold text-blue-600">SalbaKitaPH</span> —
                    your trusted disaster awareness and response app.
                </p>
                <a href="{{ route('login') }}" class="btn normal-case px-6 rounded-lg bg-blue-600 hover:bg-blue-700 text-white border-none shadow-sm">
                    Get Started
                </a>
            </div>

            <div class="relative flex justify-center items-center">
                <img src="{{ asset('css/images/flat-emergency-team-design.png') }}"
                    alt="Disaster"
                    class="w-[420px] lg:w-[860px] h-auto object-cover z-10">

                <div class="absolute inset-0 flex flex-wrap justify-center items-center gap-4 pointer-events-none z-20">
                    <div class="card w-44 rounded-md shadow-md absolute top-25 left-0 float" style="animation-delay: 0s;">
                        <div class="card-body text-center p-4">
                            <h3 class="text-sm font-semibold text-blue-700 mb-1">📢 Report</h3>
                            <p class="text-xs text-gray-600">Submit verified disaster reports fast.</p>
                        </div>
                    </div>

                    <div class="card w-44 border border-gray-200 rounded-xl shadow-lg absolute top-60 right-4 float" style="animation-delay: 1s;">
                        <div class="card-body text-center p-4">
                            <h3 class="text-sm font-semibold text-blue-700 mb-1">🛰 Stay Updated</h3>
                            <p class="text-xs text-gray-600">Get real-time warnings anytime.</p>
                        </div>
                    </div>

                    <div class="card w-44 border border-gray-200 rounded-xl shadow-lg absolute bottom-4 left-12 float" style="animation-delay: 2s;">
                        <div class="card-body text-center p-4">
                            <h3 class="text-sm font-semibold text-blue-700 mb-1">🤝 Support</h3>
                            <p class="text-xs text-gray-600">Connect and help your community.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
