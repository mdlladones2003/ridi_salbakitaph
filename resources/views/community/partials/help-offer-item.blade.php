<div class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-md transition rounded-md p-4">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center text-base-content gap-2">
            <x-lucide-hand-heart class="w-5 h-5" />
            <h4 class="font-semibold text-base capitalize text-base-content">
                {{ $offer->offer_type }}
            </h4>
        </div>

        @if(auth()->id() === $offer->user_id || auth()->user()->role === 'official')
            <form method="POST" action="{{ route('help-offers.toggle', $offer) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full transition
                    {{ $offer->is_available
                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                    <x-lucide-check-circle class="w-3.5 h-3.5" />
                    {{ $offer->is_available ? 'Available' : 'Unavailable' }}
                </button>
            </form>
        @else
            <span class="flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full
                {{ $offer->is_available
                    ? 'bg-green-100 text-green-700'
                    : 'bg-gray-100 text-gray-500' }}">
                <x-lucide-circle class="w-3.5 h-3.5" />
                {{ $offer->is_available ? 'Available' : 'Unavailable' }}
            </span>
        @endif
    </div>

    <p class="text-sm text-base-content/80 leading-relaxed mb-3">
        {{ $offer->description ?? 'No description provided.' }}
    </p>

    <div class="flex flex-wrap gap-3 text-xs text-base-content/70">
        @if($offer->capacity)
            <div class="flex items-center gap-1">
                <x-lucide-users class="w-3.5 h-3.5 text-primary/70" />
                <span>Capacity: <strong>{{ $offer->capacity }}</strong></span>
            </div>
        @endif

        @if($offer->valid_until)
            <div class="flex items-center gap-1">
                <x-lucide-clock class="w-3.5 h-3.5 text-primary/70" />
                <span>Valid until {{ \Carbon\Carbon::parse($offer->valid_until)->diffForHumans() }}</span>
            </div>
        @endif
    </div>

    <div class="mt-3 pt-2 border-t border-base-200 text-xs text-base-content/60">
        <div class="flex items-center gap-2">
            <x-lucide-user class="w-3.5 h-3.5 text-primary/60" />
            <span>
                Offered by
                <span class="font-medium text-base-content">
                    {{ $offer->user->first_name }} {{ $offer->user->last_name }}
                </span>
            </span>
        </div>
    </div>
</div>
