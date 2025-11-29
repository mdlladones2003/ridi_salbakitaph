<div x-show="activeTab === 'alerts'" x-transition>
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Community Alerts</h2>

    @forelse ($alerts as $alert)
        <div class="p-4 mb-3 bg-white shadow-sm rounded-md border-l-4
            @switch($alert->severity)
                @case('critical') border-red-500 @break
                @case('warning') border-yellow-500 @break
                @default border-blue-500
            @endswitch">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-lg text-gray-800 capitalize">{{ $alert->disasterUpdate->type }}</h3>
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ $alert->sent_at->format('M d, Y · h:i A') }}</span>
            </div>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $alert->message }}</p>
        </div>
    @empty
        <p class="text-gray-500">No active alerts at this time.</p>
    @endforelse

    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
