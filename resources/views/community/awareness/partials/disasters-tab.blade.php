<div x-show="activeTab === 'disasters'" x-transition>
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Community Disaster Updates</h2>

    @forelse ($disasters as $disaster)
        <div class="p-4 mb-3 bg-white shadow rounded-md border-l-4
            @switch(strtolower($disaster->type))
                @case('flood') border-blue-500 @break
                @case('fire') border-red-500 @break
                @case('earthquake') border-yellow-500 @break
                @case('typhoon') border-indigo-500 @break
                @case('landslide') border-green-500 @break
                @default border-gray-400
            @endswitch">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-lg text-gray-800 capitalize">{{ $disaster->type }}</h3>
                <p class="text-xs text-gray-400 whitespace-nowrap">{{ $disaster->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <p class="text-sm text-gray-700 leading-relaxed">{{ Str::limit($disaster->content, 120) }}</p>
        </div>
    @empty
        <p class="text-gray-500">No disaster updates available.</p>
    @endforelse

    <div class="mt-4">
        {{ $disasters->links() }}
    </div>
</div>
