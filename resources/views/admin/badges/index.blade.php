<x-app-layout>
  <div class="p-6 max-w-7xl mx-auto mt-20">
    <!-- Page Heading -->
    <h1 class="text-3xl font-bold mb-6 text-base-content">Badge Management</h1>

    <!-- Stats Widgets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-8">
      @foreach ([
        ['title' => 'Total Badges', 'value' => $stats['total_badges'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4a2 2 0 002 2h2l1 7H7M19 3v4a2 2 0 01-2 2h-2l-1 7h6"/></svg>'],
        ['title' => 'Reporter Badges', 'value' => $stats['reporter_badges'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5l2 3h5v9a2 2 0 01-2 2z"/></svg>'],
        ['title' => 'Verifier Badges', 'value' => $stats['verifier_badges'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 4v16m-8-8h16"/></svg>'],
        ['title' => 'Helper Badges', 'value' => $stats['helper_badges'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v6M6 9v6m6-9v12M4 12h16"/></svg>'],
        ['title' => 'Hero Badges', 'value' => $stats['hero_badges'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L6 21l3.75-10.5M14.25 7L18 3l-3.75 10.5"/></svg>'],
      ] as $stat)
      <div class="bg-base-200 rounded-lg shadow p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
        <div class="mb-4">{!! $stat['icon'] !!}</div>
        <div class="text-sm font-semibold tracking-wide uppercase text-base-content">{{ $stat['title'] }}</div>
        <div class="text-3xl font-extrabold text-base-content mt-2">{{ $stat['value'] }}</div>
      </div>
      @endforeach
    </div>

    <!-- Modal Trigger Button -->
    <label for="badge-modal" class="btn btn-primary mb-6 cursor-pointer">Award New Badge</label>

    <!-- Modal -->
    <input type="checkbox" id="badge-modal" class="modal-toggle" />
    <div class="modal modal-bottom sm:modal-middle">
      <div class="modal-box relative max-w-lg bg-base-100 rounded-lg shadow-lg">
        <label for="badge-modal" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
        <h3 class="text-lg font-bold mb-4 text-base-content">Award New Badge</h3>

        <form action="{{ route('admin.badges.store') }}" method="POST" class="space-y-4">
          @csrf

          <div class="form-control">
            <label for="user_id" class="label"><span class="label-text font-semibold text-base-content">User</span></label>
            <select id="user_id" name="user_id" class="select select-bordered w-full" required>
              <option disabled selected>Select a User</option>
              @foreach (\App\Models\User::orderBy('first_name')->get() as $user)
              <option value="{{ $user->user_id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
              @endforeach
            </select>
            @error('user_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="form-control">
            <label for="badge_type" class="label"><span class="label-text font-semibold text-base-content">Badge Type</span></label>
            <select id="badge_type" name="badge_type" class="select select-bordered w-full" required>
              <option disabled selected>Select Badge Type</option>
              <option value="reporter">Reporter</option>
              <option value="verifier">Verifier</option>
              <option value="helper">Helper</option>
              <option value="hero">Hero</option>
            </select>
            @error('badge_type')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          <button type="submit" class="btn btn-primary w-full">Award Badge</button>
        </form>
      </div>
    </div>

    <!-- Badges Table -->
    <div class="overflow-x-auto rounded-lg border border-base-300">
      <table class="table w-full table-zebra">
        <thead>
          <tr class="text-base-content">
            <th>User</th>
            <th>Badge Type</th>
            <th>Date Earned</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($badges as $badge)
          <tr class="hover:bg-base-200 transition-colors">
            <td>
              {{ $badge->user ? $badge->user->first_name . ' ' . $badge->user->last_name : 'Unknown User' }}<br />
              <small class="opacity-60">{{ $badge->user?->email ?? '' }}</small>
            </td>
            <td class="capitalize">{{ $badge->badge_type }}</td>
            <td>{{ $badge->earned_at->format('M d, Y') }}</td>
            <td>
              <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('Remove this badge?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-error">Remove</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-base-content">No badges awarded yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $badges->links() }}
    </div>
  </div>

  <!-- Tom Select CSS & JS for searchable user dropdown -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      new TomSelect('#user_id', {
        dropdownDirection: 'bottom',
        maxDropdownHeight: '200px',
        dropdownAutoWidth: true,
        create: false,
      });
    });
  </script>
</x-app-layout>
