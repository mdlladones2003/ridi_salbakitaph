<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-award class="w-6 h-6" /> Badge Management
            </h1>
            <label for="badge-modal" class="btn bg-blue-600 hover:bg-blue-700 btn-sm text-white flex items-center gap-1 cursor-pointer">
                <x-lucide-plus-circle class="w-4 h-4" /> Award New Badge
            </label>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ([
                ['title' => 'Total Badges', 'value' => $stats['total_badges'], 'icon' => 'award', 'bg' => 'bg-blue-600'],
                ['title' => 'Reporter Badges', 'value' => $stats['reporter_badges'], 'icon' => 'file-text', 'bg' => 'bg-yellow-500'],
                ['title' => 'Verifier Badges', 'value' => $stats['verifier_badges'], 'icon' => 'check-circle', 'bg' => 'bg-green-600'],
                ['title' => 'Helper Badges', 'value' => $stats['helper_badges'], 'icon' => 'hand-heart', 'bg' => 'bg-cyan-600'],
                ['title' => 'Hero Badges', 'value' => $stats['hero_badges'], 'icon' => 'star', 'bg' => 'bg-purple-600'],
            ] as $stat)
                <div class="relative {{ $stat['bg'] }} text-white rounded-md shadow-sm hover:shadow-md p-5 transition-all duration-300">
                    <div class="absolute top-3 right-3 opacity-70">
                        <x-dynamic-component :component="'lucide-' . $stat['icon']" class="w-6 h-6" />
                    </div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide mb-1">{{ $stat['title'] }}</h3>
                    <p class="text-3xl font-extrabold">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <input type="checkbox" id="badge-modal" class="modal-toggle" />
        <div class="modal modal-bottom sm:modal-middle">
            <div class="modal-box max-w-md bg-base-100 rounded-md">
                <label for="badge-modal" class="btn btn-sm btn-circle absolute right-3 top-3">✕</label>
                <h3 class="text-lg font-bold text-base-content mb-4 flex items-center gap-2">
                    <x-lucide-award class="w-5 h-5" /> Award New Badge
                </h3>

                <form action="{{ route('admin.badges.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="font-semibold text-sm text-base-content">User</label>
                        <select name="user_id" class="select select-bordered w-full mt-1" required>
                            <option disabled selected>Select a User</option>
                            @foreach (\App\Models\User::orderBy('first_name')->get() as $user)
                                <option value="{{ $user->user_id }}">
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm text-base-content">Badge Type</label>
                        <select name="badge_type" class="select select-bordered w-full mt-1" required>
                            <option disabled selected>Select Badge Type</option>
                            <option value="reporter">Reporter</option>
                            <option value="verifier">Verifier</option>
                            <option value="helper">Helper</option>
                            <option value="hero">Hero</option>
                        </select>
                        @error('badge_type') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full mt-2">
                        <x-lucide-gift class="w-4 h-4 mr-1" /> Award Badge
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white border border-base-300 rounded-md shadow-sm overflow-hidden">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">User</th>
                        <th class="py-3 px-4 text-left">Badge Type</th>
                        <th class="py-3 px-4 text-left">Date Earned</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($badges as $badge)
                        <tr class="hover:bg-base-100 transition">
                            <td class="py-3 px-4">
                                <div class="font-medium">
                                    {{ $badge->user ? $badge->user->first_name . ' ' . $badge->user->last_name : 'Unknown User' }}
                                </div>
                                <div class="text-sm opacity-60">{{ $badge->user?->email ?? '' }}</div>
                            </td>
                            <td class="capitalize py-3 px-4">{{ $badge->badge_type }}</td>
                            <td class="py-3 px-4">{{ $badge->earned_at->format('M d, Y') }}</td>
                            <td class="text-center py-3 px-4">
                                <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST"
                                    onsubmit="return confirm('Remove this badge?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-error flex items-center gap-1">
                                        <x-lucide-trash-2 class="w-4 h-4" /> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-base-content py-6">No badges awarded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $badges->links() }}
        </div>
    </div>
</x-app-layout>
