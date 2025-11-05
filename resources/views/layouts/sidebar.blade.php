<aside class="sidebar-container bg-base-200 shadow-xl">
    <div class="flex justify-center items-center border-b border-base-300 h-[65px]">
        <h2 class="text-xl font-extrabold mb-2 text-blue-600">Admin Panel</h2>
    </div>

    <ul class="menu mx-auto text-base-content mt-4 space-y-2">
        <li>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-home class="w-5 h-5" />
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-users class="w-5 h-5" />
                Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.badges.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.badges.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-star class="w-5 h-5" />
                Badges
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reports.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.reports.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-file-text class="w-5 h-5" />
                Reports
            </a>
        </li>
        <li>
            <a href="{{ route('admin.alerts.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.alerts.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-bell class="w-5 h-5" />
                Alerts
            </a>
        </li>
        <li>
            <a href="{{ route('admin.disasters.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.disasters.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-zap class="w-5 h-5" />
                Disaster Updates
            </a>
        </li>
        <li>
            <a href="{{ route('admin.barangays.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.barangays.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-map-pin class="w-5 h-5" />
                Barangays
            </a>
        </li>
        <li>
            <a href="{{ route('admin.evacuation-centers.index') }}"
                class="flex items-center gap-3 rounded-md py-2 px-3 transition-colors duration-200
                        {{ request()->routeIs('admin.evacuation-centers.*')
                            ? 'bg-blue-600 text-white'
                            : 'hover:bg-blue-100 text-gray-700 hover:text-blue-600' }}">
                <x-lucide-building class="w-5 h-5" />
                Evacuation Centers
            </a>
        </li>
    </ul>
</aside>
