<aside class="w-64 bg-base-200 shadow-xl min-h-screen">
  <div class="p-4">
    <h2 class="text-xl font-extrabold mb-4 text-primary">Admin Panel</h2>
  </div>
  <ul class="menu px-4 py-0 bg-base-200 text-base-content rounded-lg">
    <li>
      <a href="{{ route('admin.dashboard') }}"
         class="{{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-2 2v6a2 2 0 002 2h3m-6 0a2 2 0 01-2-2v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4" />
        </svg>
        Dashboard
      </a>
    </li>
    <li>
      <a href="{{ route('admin.users.index') }}"
         class="{{ request()->routeIs('admin.users.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Users
      </a>
    </li>
    <li>
      <a href="{{ route('admin.badges.index') }}"
         class="{{ request()->routeIs('admin.badges.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.19c.969 0 1.371 1.24.588 1.81l-3.397 2.463a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118L12 13.347l-3.397 2.463c-.785.57-1.84-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L5.59 9.393c-.783-.57-.38-1.81.588-1.81h4.19a1 1 0 00.95-.69l1.286-3.966z" />
        </svg>
        Badges
      </a>
    </li>
    <li>
      <a href="{{ route('admin.reports.index') }}"
         class="{{ request()->routeIs('admin.reports.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Reports
      </a>
    </li>
    <li>
      <a href="{{ route('admin.disasters.index') }}"
         class="{{ request()->routeIs('admin.disasters.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12l4.243-4.243m-6.829 6.829L6.343 7.757a4 4 0 015.657-5.657l1.414 1.414a4 4 0 010 5.657l-1.414 1.414z" />
        </svg>
        Disaster Updates
      </a>
    </li>
    <li>
      <a href="{{ route('admin.alerts.index') }}"
         class="{{ request()->routeIs('admin.alerts.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Alerts
      </a>
    </li>
    <li>
      <a href="{{ route('admin.barangays.index') }}"
         class="{{ request()->routeIs('admin.barangays.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 1.104-.896 2-2 2a2 2 0 01-2-2c0-1.104.896-2 2-2a2 2 0 012 2z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 20h12M6 16h12m-7-6h4" />
        </svg>
        Barangays
      </a>
    </li>
    <li>
      <a href="{{ route('admin.evacuation-centers.index') }}"
         class="{{ request()->routeIs('admin.evacuation-centers.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 14h10M10 18h4" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />
        </svg>
        Evacuation Center
      </a>
    </li>
    <li>
      <a href="{{ route('admin.evacuation-routes.index') }}"
         class="{{ request()->routeIs('admin.evacuation-routes.*') ? 'active bg-primary text-primary-content' : 'hover:bg-primary/40 hover:text-primary-content' }} flex items-center gap-3 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17l6-10M15 17l-6-10" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />
        </svg>
        Evacuation Routes
      </a>
    </li>
  </ul>
</aside>
