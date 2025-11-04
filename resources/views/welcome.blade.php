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
    </style>
</head>

<body class="min-h-screen flex flex-col text-gray-800 bg-base-100">

    <nav class="navbar bg-base-100 border-b border-gray-200 px-8 py-4">
        <div class="flex-1">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-700 tracking-tight">
                SalbaKitaPH
            </a>
        </div>
        <div class="flex-none">
            <a href="{{ route('login') }}"
               class="btn normal-case px-6 rounded-lg bg-blue-600 hover:bg-blue-700 text-white border-none shadow-sm">
                Get Started
            </a>
        </div>
    </nav>

    <section class="min-h-[90vh] flex flex-col lg:flex-row items-center justify-center px-10 gap-12 fade-in">

        <div class="flex-1 text-center lg:text-left space-y-6">
            <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-800 leading-tight">
                Stay Alert. Stay Safe.
                <span class="text-blue-700">Together.</span>
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed max-w-md mx-auto lg:mx-0">
                Report disasters, get real-time alerts, and protect your community with
                <span class="font-semibold text-blue-600">SalbaKitaPH</span> —
                your trusted disaster awareness and response app.
            </p>
            <a href="{{ route('login') }}"
               class="btn normal-case px-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white border-none shadow-sm">
                Get Started
            </a>
        </div>

        <div class="flex-1 grid grid-cols-2 gap-4 justify-center">
            <img src="{{ asset('css/images/disaster.jpg') }}" alt="Disaster"
                class="w-full h-52 lg:h-64 object-cover rounded-xl image-tilt">
            <img src="{{ asset('css/images/flood.jpg') }}" alt="Flood"
                class="w-full h-52 lg:h-64 object-cover rounded-xl image-tilt">
            <img src="{{ asset('css/images/fire_truck.jpg') }}" alt="Fire Truck"
                class="w-full h-52 lg:h-64 object-cover rounded-xl image-tilt">
            <img src="{{ asset('css/images/typhoon.jpg') }}" alt="Typhoon"
                class="w-full h-52 lg:h-64 object-cover rounded-xl image-tilt">
        </div>
    </section>

    <section class="py-20 bg-base-200 fade-in">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-10 text-center text-gray-800">Why Use SalbaKitaPH?</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="card bg-base-100 border border-gray-200 rounded-xl hover:border-blue-500 transition">
                    <div class="card-body text-center">
                        <h3 class="text-lg font-semibold text-blue-700 mb-2">📢 Report Disasters</h3>
                        <p class="text-gray-600">Quickly submit verified reports to alert authorities and the public.</p>
                    </div>
                </div>
                <div class="card bg-base-100 border border-gray-200 rounded-xl hover:border-blue-500 transition">
                    <div class="card-body text-center">
                        <h3 class="text-lg font-semibold text-blue-700 mb-2">🛰 Stay Updated</h3>
                        <p class="text-gray-600">Receive real-time updates and warnings during critical events.</p>
                    </div>
                </div>
                <div class="card bg-base-100 border border-gray-200 rounded-xl hover:border-blue-500 transition">
                    <div class="card-body text-center">
                        <h3 class="text-lg font-semibold text-blue-700 mb-2">🤝 Community Support</h3>
                        <p class="text-gray-600">Coordinate with local responders and help your neighbors in need.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
