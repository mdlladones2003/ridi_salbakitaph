<x-app-layout>
    <div
        x-data="{
            activeTab: '{{ $activeTab ?? 'reports' }}',
            open: true
        }"
        x-init="
            const urlTab = new URLSearchParams(window.location.search).get('tab');
            if (urlTab) activeTab = urlTab;
        "
        class="flex flex-row max-w-7xl mx-auto">

        {{-- Sidebar Navigation --}}
        @include('community.awareness.partials.sidebar')

        <main class="flex-1 p-6 overflow-y-auto">
            {{-- Reports Tab --}}
            @include('community.awareness.partials.reports-tab', ['reports' => $reports, 'barangays' => $barangays])

            {{-- Alerts Tab --}}
            @include('community.awareness.partials.alerts-tab', ['alerts' => $alerts])

            {{-- Disasters Tab --}}
            @include('community.awareness.partials.disasters-tab', ['disasters' => $disasters])
        </main>
    </div>

    @push('scripts')
        @include('community.awareness.partials.map-scripts')
    @endpush
</x-app-layout>
