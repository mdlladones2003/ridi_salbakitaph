<x-app-layout>
    <div class="p-4 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-primary">Users</h1>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
            <input
                type="text"
                name="search"
                placeholder="Search by name, email, phone"
                value="{{ request('search') }}"
                class="input input-bordered w-full sm:w-auto flex-grow"
            />

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

            <button type="submit" class="btn btn-primary w-full sm:w-auto whitespace-nowrap px-6 py-2">
                Filter
            </button>
        </form>

        <div class="overflow-x-auto rounded-lg border border-base-300">
            <table class="table w-full table-zebra">
                <thead>
                    <tr>
                        <th class="text-left text-base-content/80">Name</th>
                        <th class="text-left text-base-content/80">Email</th>
                        <th class="text-left text-base-content/80">Role</th>
                        <th class="text-center text-base-content/80">Status</th>
                        <th class="text-center text-base-content/80">Reputation</th>
                        <th class="text-center text-base-content/80">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="text-base-content">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="text-base-content">{{ $user->email }}</td>
                        <td class="text-base-content">{{ ucfirst($user->role) }}</td>
                        <td class="text-center">
                            @if ($user->is_active)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-error">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center text-base-content">{{ $user->reputation_score ?? 'N/A' }}</td>
                        <td class="text-center space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-info">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-warning">Edit</a>

                            <!-- Delete Modal Trigger -->
                            <label for="modal-delete-{{ $user->user_id }}" class="btn btn-xs btn-error cursor-pointer">Delete</label>

                            <!-- Modal -->
                            <input type="checkbox" id="modal-delete-{{ $user->user_id }}" class="modal-toggle"/>
                            <div class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg text-error">Confirm Delete</h3>
                                    <p class="py-4 text-base-content">Are you sure you want to delete <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>?</p>
                                    <p class="text-sm text-error mb-4">This action cannot be undone.</p>
                                    <div class="modal-action">
                                        <label for="modal-delete-{{ $user->user_id }}" class="btn btn-ghost">Cancel</label>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error">Delete User</button>
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
