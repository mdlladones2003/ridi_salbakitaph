<nav
    x-data="{ navOpen: false, profileOpen: false }"
    class="bg-white border-b border-gray-100 top-0 left-0 right-0 z-50"
    style="height: 4rem;"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">

        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
            <a href="#" class="text-xl font-bold text-gray-800 select-none">
                SalbaKitaPH
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden sm:flex space-x-8 flex-1 justify-center items-center">
            @auth
                @if (in_array(Auth::user()->role, ['user', 'official', 'volunteer']))
                    <a href="{{ route('community.index') }}"
                        class="flex flex-col items-center transition duration-150 ease-in-out {{ request()->routeIs('community.index') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z" />
                        </svg>
                        {{-- <span class="text-sm leading-5">Posts</span> --}}
                    </a>

                    <a href="{{ route('community.map') }}"
                        class="flex flex-col items-center transition duration-150 ease-in-out {{ request()->routeIs('community.map') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}" title="Map">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.447-1.894L9 2m6 18l5.447-2.724A2 2 0 0021 15.382V5.618a2 2 0 00-1.447-1.894L15 2m-6 0v18m6-18v18" />
                        </svg>
                        {{-- <span class="text-sm leading-5">Map</span> --}}
                    </a>

                    <a href="{{ route('community.alerts') }}"
                        class="flex flex-col items-center transition duration-150 ease-in-out {{ request()->routeIs('community.alerts') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}" title="Alerts">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4m0 4h.01" />
                        </svg>
                        {{-- <span class="text-sm leading-5">Alerts</span> --}}
                    </a>

                    <a href="{{ route('community.search') }}"
                        class="flex flex-col items-center transition duration-150 ease-in-out {{ request()->routeIs('community.search') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}" title="Search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{-- <span class="text-sm leading-5">Search</span> --}}
                    </a>
                @endif
            @endauth
        </div>

        <!-- Profile Dropdown -->
        <div class="relative flex-shrink-0 ml-4" @click.away="profileOpen = false">
            <button @click="profileOpen = !profileOpen" aria-haspopup="true" aria-expanded="false"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150"
                aria-label="User menu"
            >
                @php
                    $user = Auth::user();
                    $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));
                @endphp
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-content font-semibold select-none">
                    {{ $initials }}
                </div>
                <svg class="ml-2 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="profileOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50" style="display: none;">
                <x-dropdown-link :href="route('profile.edit')" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile menu button -->
        <div class="sm:hidden ml-4">
            <button @click="navOpen = !navOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': navOpen, 'inline-flex': !navOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !navOpen, 'inline-flex': navOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="navOpen" x-transition class="sm:hidden border-t border-gray-200">
        @auth
            @if (in_array(Auth::user()->role, ['user', 'official', 'volunteer']))
                <div class="pt-2 pb-3 space-y-1 px-2">
                    <a href="{{ route('community.index') }}" class="block px-3 py-2 rounded-md font-medium {{ request()->routeIs('community.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">
                        Posts
                    </a>

                    <a href="{{ route('community.map') }}" class="block px-3 py-2 rounded-md font-medium {{ request()->routeIs('community.map') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">
                        Map
                    </a>

                    <a href="{{ route('community.alerts') }}" class="block px-3 py-2 rounded-md font-medium {{ request()->routeIs('community.alerts') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">
                        Alerts
                    </a>

                    <a href="{{ route('community.search') }}" class="block px-3 py-2 rounded-md font-medium {{ request()->routeIs('community.search') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">
                        Search
                    </a>
                </div>
            @endif
        @endauth
    </div>
</nav>
