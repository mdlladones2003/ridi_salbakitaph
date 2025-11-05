<x-app-layout>
    <div class="px-6 py-4 max-w-7xl mx-auto space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-user-cog class="w-6 h-6" />
                Edit User
            </h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4" /> Back
            </a>
        </div>

        <div class="bg-white border border-base-300 shadow-sm rounded-md p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <section>
                    <h2 class="text-lg font-semibold text-blue-600 mb-2 flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-blue-600" /> Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="first_name" class="font-semibold text-sm text-base-content/80">First Name</label>
                            <input type="text" id="first_name" name="first_name"
                                value="{{ old('first_name', $user->first_name) }}"
                                class="input input-bordered w-full mt-1" required>
                        </div>

                        <div>
                            <label for="last_name" class="font-semibold text-sm text-base-content/80">Last Name</label>
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $user->last_name) }}"
                                class="input input-bordered w-full mt-1" required>
                        </div>

                        <div>
                            <label for="email" class="font-semibold text-sm text-base-content/80">Email</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="input input-bordered w-full mt-1" required>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-semibold text-blue-600 mb-2 flex items-center gap-2">
                        <x-lucide-phone class="w-5 h-5 text-blue-600" /> Account & Contact
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="phone_number" class="font-semibold text-sm text-base-content/80">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $user->phone_number) }}"
                                class="input input-bordered w-full mt-1">
                        </div>

                        <div>
                            <label for="role" class="font-semibold text-sm text-base-content/80">Role</label>
                            <select id="role" name="role" class="select select-bordered w-full mt-1" required>
                                @php
                                    $roles = ['user' => 'User', 'volunteer' => 'Volunteer', 'official' => 'Official', 'admin' => 'Admin'];
                                @endphp
                                @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="reputation_score" class="font-semibold text-sm text-base-content/80">Reputation Score</label>
                            <input type="number" id="reputation_score" name="reputation_score" min="0" max="10000"
                                value="{{ old('reputation_score', $user->reputation_score) }}"
                                class="input input-bordered w-full mt-1">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-6 mt-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_verified" class="checkbox checkbox-sm"
                                value="1" @checked(old('is_verified', $user->is_verified))>
                            <span class="text-sm font-medium">Verified</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" class="checkbox checkbox-sm"
                                value="1" @checked(old('is_active', $user->is_active))>
                            <span class="text-sm font-medium">Active</span>
                        </label>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-semibold text-blue-600 mb-2 flex items-center gap-2">
                        <x-lucide-lock class="w-5 h-5 text-blue-600" /> Security
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="font-semibold text-sm text-base-content/80">
                                New Password <span class="text-xs text-base-content/60">(leave blank to keep current)</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="input input-bordered w-full mt-1">
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-3 pt-4 border-t border-base-200">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Cancel</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm px-5">
                        <x-lucide-save class="w-4 h-4 mr-1" /> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
