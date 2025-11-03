<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        <!-- Left: Logo -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('community.index') }}" class="text-xl font-semibold text-blue-700 hover:text-blue-800">
                SalbaKitaPH
            </a>
        </div>

        <!-- Right: Navigation + Search + Profile -->
        <div class="flex items-center space-x-6">
            @auth
                @if (in_array(Auth::user()->role, ['user', 'official', 'volunteer']))

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('community.index') }}"
                            class="pb-1 border-b-2 transition font-medium
                            {{ request()->routeIs('community.index') ? 'text-blue-600 border-blue-600' : 'text-gray-700 border-transparent hover:text-blue-600 hover:border-blue-400' }}">
                            Posts
                        </a>

                        <a href="{{ route('community.map') }}"
                            class="pb-1 border-b-2 transition font-medium
                            {{ request()->routeIs('community.map') ? 'text-blue-600 border-blue-600' : 'text-gray-700 border-transparent hover:text-blue-600 hover:border-blue-400' }}">
                            Map
                        </a>

                        <a href="{{ route('community.alerts') }}"
                            class="pb-1 border-b-2 transition font-medium
                            {{ request()->routeIs('community.alerts') ? 'text-blue-600 border-blue-600' : 'text-gray-700 border-transparent hover:text-blue-600 hover:border-blue-400' }}">
                            Alerts
                        </a>
                    </div>

                    <!-- Enhanced Search Bar -->
                    <form action="{{ route('community.search') }}" method="GET"
                        class="relative flex items-center w-64 group">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Search"
                            class="w-full border border-gray-300 rounded-full py-2 pl-10 pr-4 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 group-hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 h-4 w-4 text-gray-400 group-hover:text-blue-500 transition duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center bg-gray-100 hover:bg-gray-200 rounded-full px-3 py-2 transition">
                            @php
                                $user = Auth::user();
                                $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));
                            @endphp
                            <span
                                class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full font-semibold">
                                {{ $initials }}
                            </span>
                            <svg class="ml-2 w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition.opacity.scale.95 x-cloak
                            @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>

                @endif
            @endauth
        </div>
    </div>
</nav>
