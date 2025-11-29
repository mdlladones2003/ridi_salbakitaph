<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-users class="w-6 h-6" />
                User Management
            </h1>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}"
              class="bg-white border border-base-300 rounded-md shadow-sm p-4 flex flex-col sm:flex-row sm:items-center gap-3">

            <div class="flex-1">
                <input type="text" name="search" placeholder="🔍 Search by name, email, or phone"
                       value="{{ request('search') }}" class="input input-bordered w-full" />
            </div>

            <select name="role" class="select select-bordered w-full sm:w-40">
                <option value="">All Roles</option>
                <option value="user" @selected(request('role')=='user')>User</option>
                <option value="volunteer" @selected(request('role')=='volunteer')>Volunteer</option>
                <option value="official" @selected(request('role')=='official')>Official</option>
                <option value="admin" @selected(request('role')=='admin')>Admin</option>
            </select>

            <select name="is_active" class="select select-bordered w-full sm:w-32">
                <option value="">All Status</option>
                <option value="1" @selected(request('is_active')=='1')>Active</option>
                <option value="0" @selected(request('is_active')=='0')>Inactive</option>
            </select>

            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6">
                <x-lucide-filter class="w-4 h-4" /> Filter
            </button>
        </form>

        <div class="bg-white border border-base-300 rounded-md shadow-sm overflow-hidden">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Reputation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">
                                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-xs text-base-content/60">{{ ucfirst($user->role) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-sm text-base-content">{{ $user->email }}</td>
                            <td class="text-sm">{{ ucfirst($user->role) }}</td>

                            <td class="text-center">
                                @if ($user->is_active)
                                    <span class="badge bg-green-100 text-green-700 border-none">Active</span>
                                @else
                                    <span class="badge bg-red-100 text-red-700 border-none">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center text-sm">{{ $user->reputation_score ?? 'N/A' }}</td>

                            <td class="text-center whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-xs text-yellow-600 hover:bg-yellow-50">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>

                                <label for="modal-delete-{{ $user->user_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                    <x-lucide-trash class="w-4 h-4" />
                                </label>

                                <input type="checkbox" id="modal-delete-{{ $user->user_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                <x-lucide-alert-triangle class="w-5 h-5" />
                                            </div>
                                            <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-sm text-base-content">
                                                You are about to delete <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>.
                                            </p>
                                            <p class="text-xs text-red-500 font-medium">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-action mt-5 flex justify-end gap-2">
                                            <label for="modal-delete-{{ $user->user_id }}"
                                                class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                                                Cancel
                                            </label>

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm bg-red-600 hover:bg-red-700 text-white flex items-center px-2">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <label for="modal-delete-{{ $user->user_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-base-content/60">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
