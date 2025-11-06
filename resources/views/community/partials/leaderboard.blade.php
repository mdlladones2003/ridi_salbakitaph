<div
    x-data="{ tab: 'reporters' }"
    class="card bg-base-100 shadow-lg border border-base-300 rounded-xl p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold flex text-base-content items-center gap-2">
            <x-lucide-trophy class="w-4 h-4" />
            Leaderboards
        </h2>
    </div>

    <div class="flex flex-wrap justify-center gap-2 border-b border-base-300 pb-3 mb-6">
        @foreach ([
            'reporters' => ['label' => 'Reporters'],
            'verifiers' => ['label' => 'Verifiers'],
            'helpers' => ['label' => 'Helpers'],
            'reputation' => ['label' => 'Reputation'],
        ] as $key => $tab)
            <button
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}'
                    ? 'btn btn-sm btn-primary shadow-sm'
                    : 'btn btn-sm btn-ghost text-gray-600 hover:bg-base-200'"
                class="flex items-center gap-2 transition-all duration-150">
                <span class="hidden sm:inline font-medium">{{ $tab['label'] }}</span>
            </button>
        @endforeach
    </div>

    <div class="space-y-4">
        <div x-show="tab === 'reporters'" x-transition x-cloak>
            <h3 class="font-semibold mb-2 flex items-center gap-2">Top Reporters</h3>
            <ul class="space-y-2">
                @forelse($topReporters as $user)
                    <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg hover:bg-base-300 transition">
                        <span class="font-medium truncate">{{ $user->full_name ?? "{$user->first_name} {$user->last_name}" }}</span>
                        <span class="badge badge-primary">{{ $user->report_verified_count }}</span>
                    </li>
                @empty
                    <li class="text-center opacity-60 py-2">No verified reports yet.</li>
                @endforelse
            </ul>
        </div>

        <div x-show="tab === 'verifiers'" x-transition x-cloak>
            <h3 class="font-semibold mb-2 flex items-center gap-2">Top Verifiers</h3>
            <ul class="space-y-2">
                @forelse($topVerifiers as $user)
                    <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg hover:bg-base-300 transition">
                        <span class="font-medium truncate">{{ $user->full_name ?? "{$user->first_name} {$user->last_name}" }}</span>
                        <span class="badge badge-info">{{ $user->verified_count }}</span>
                    </li>
                @empty
                    <li class="text-center opacity-60 py-2">No verifiers yet.</li>
                @endforelse
            </ul>
        </div>

        <div x-show="tab === 'helpers'" x-transition x-cloak>
            <h3 class="font-semibold mb-2 flex items-center gap-2">Top Helpers</h3>
            <ul class="space-y-2">
                @forelse($topHelpers as $user)
                    <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg hover:bg-base-300 transition">
                        <span class="font-medium truncate">{{ $user->full_name ?? "{$user->first_name} {$user->last_name}" }}</span>
                        <span class="badge badge-warning">{{ $user->help_offers_count }}</span>
                    </li>
                @empty
                    <li class="text-center opacity-60 py-2">No data available.</li>
                @endforelse
            </ul>
        </div>

        <div x-show="tab === 'reputation'" x-transition x-cloak>
            <h3 class="font-semibold mb-2 flex items-center gap-2">Top Reputation</h3>
            <ul class="space-y-2">
                @forelse($topByReputation as $user)
                    <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg hover:bg-base-300 transition">
                        <span class="font-medium truncate">{{ $user->full_name ?? "{$user->first_name} {$user->last_name}" }}</span>
                        <span class="badge badge-accent">{{ $user->reputation_score }}</span>
                    </li>
                @empty
                    <li class="text-center opacity-60 py-2">No data available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
