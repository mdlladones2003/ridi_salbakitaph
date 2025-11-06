<x-app-layout>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-4">
        <aside class="space-y-4 lg:col-span-3 order-2 lg:order-1">
            @php
                $latestCheckIn = auth()->user()->checkIns()->latest('created_at')->first();
                $userStatus = old('status', $latestCheckIn->status ?? '');
            @endphp

            <div class="card bg-gradient-to-b from-white to-base-200 shadow-md border border-base-300">
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-base-content flex items-center gap-2">
                            <x-lucide-user-check class="w-4 h-4" />
                            Mark Yourself As
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('check-in.store') }}" id="check-in-form">
                        @csrf

                        <div class="flex flex-col gap-3">
                            @foreach ([
                                'safe' => ['Safe', 'bg-green-100 hover:bg-green-200 border-green-400 text-green-700'],
                                'need_help' => ['Need Help', 'bg-red-100 hover:bg-red-200 border-red-400 text-red-700'],
                                'evacuating' => ['Evacuating', 'bg-yellow-100 hover:bg-yellow-200 border-yellow-400 text-yellow-800']
                            ] as $value => [$label, $style])
                                <label
                                    class="cursor-pointer transition-all border rounded-md p-2 flex flex-col items-center justify-center gap-2 text-sm font-medium {{ $style }}
                                        @if($userStatus === $value) ring-1 ring-offset-1 ring-primary @endif"
                                >
                                    <input
                                        type="radio"
                                        name="status"
                                        value="{{ $value }}"
                                        class="hidden"
                                        {{ $userStatus === $value ? 'checked' : '' }}
                                    >
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md border border-base-300">
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-base-content flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4" />
                            Active Users
                        </h3>
                        <span class="badge badge-sm bg-blue-600 text-white">{{ count($activeUsers) }}</span>
                    </div>

                    <div class="max-h-64 overflow-y-auto divide-y divide-base-200">
                        @forelse($activeUsers as $user)
                            <div class="flex items-center gap-3 py-2 hover:bg-base-200/50 rounded-lg transition-colors duration-150 px-2">
                                <!-- Avatar -->
                                <div class="avatar placeholder">
                                    <div class="bg-primary/80 text-white rounded-full w-10 h-10 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                </div>

                                <!-- User Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->last_active_at?->diffForHumans() ?? 'Recently active' }}
                                    </p>
                                </div>

                                <!-- Status Indicator -->
                                <div class="tooltip" data-tip="Online">
                                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-sm text-gray-500">
                                No active users
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @include('community.partials.leaderboard')
        </aside>

        <section class="card bg-base-100 shadow border border-base-300 lg:col-span-5 order-1 lg:order-2">
            <div class="card-body">
                <h2 class="card-title text-xl mb-4 flex items-center gap-2">Community Feed</h2>

                @include('community.partials.feed-form')
                @include('community.partials.feed-list')
            </div>
        </section>

        <aside class="space-y-4 lg:col-span-4 order-3">
            <div class="card bg-base-100 shadow border border-base-300">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="card-title text-lg">Help Offers</h3>
                        <button
                            type="button"
                            class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4"
                            onclick="document.getElementById('offerForm').classList.toggle('hidden')">
                            <x-lucide-hand-heart class="w-4 h-4" />
                            <span>Offer Help</span>
                        </button>
                    </div>

                    <div id="offerForm" class="hidden border border-base-200 rounded-md mb-4 bg-base-200">
                        @include('community.partials.help-offer-form')
                    </div>

                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @forelse($offers as $offer)
                            @include('community.partials.help-offer-item')
                        @empty
                            <p class="text-center opacity-60">No offers available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </aside>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('check-in-form');
            if (!form) return;

            const radios = Array.from(form.querySelectorAll('input[type="radio"][name="status"]'));
            let lastChecked = form.querySelector('input[type="radio"][name="status"]:checked') || null;

            // hidden input id to avoid duplicates when clearing
            const hiddenId = 'status-clear-hidden';

            radios.forEach(radio => {
                radio.addEventListener('click', function() {
                    // If the same radio was clicked again -> uncheck and submit empty
                    if (lastChecked === this) {
                        this.checked = false;
                        lastChecked = null;

                        // If hidden exists update it; otherwise create once
                        let hidden = document.getElementById(hiddenId);
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.id = hiddenId;
                            hidden.name = 'status';
                            form.appendChild(hidden);
                        }
                        hidden.value = ''; // send empty to clear status
                        form.submit();
                    } else {
                        // normal selection -> remove any previous hidden clear input
                        const existingHidden = document.getElementById(hiddenId);
                        if (existingHidden) existingHidden.remove();

                        lastChecked = this;
                        form.submit();
                    }
                });

                // also allow keyboard selection (space/enter): handle change event to be safe
                radio.addEventListener('change', function() {
                    // if radio is checked via keyboard navigation and wasn't the lastChecked, submit
                    if (this.checked && lastChecked !== this) {
                        const existingHidden = document.getElementById(hiddenId);
                        if (existingHidden) existingHidden.remove();
                        lastChecked = this;
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
