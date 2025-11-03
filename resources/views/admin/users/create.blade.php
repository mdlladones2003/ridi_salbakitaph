<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>

        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($user))
            @method('PUT')
            @endif

            <div>
                <label class="block mb-1 font-semibold" for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name"
                value="{{ old('first_name', $user->first_name ?? '') }}" class="input input-bordered w-full" required>
                @error('first_name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold" for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name"
                value="{{ old('last_name', $user->last_name ?? '') }}" class="input input-bordered w-full" required>
                @error('last_name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="input input-bordered w-full"
                required>
                @error('email')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold" for="password">{{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}</label>
                <input type="password" name="password" id="password" class="input input-bordered w-full" {{ isset($user) ? '' : 'required' }}>
                @error('password')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            @if(!isset($user))
                <div>
                    <label class="block mb-1 font-semibold" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input input-bordered w-full" required>
                </div>
            @endif

            <div>
                <label class="block mb-1 font-semibold" for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                class="input input-bordered w-full">
                @error('phone_number')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold" for="role">Role</label>
                <select name="role" id="role" class="select select-bordered w-full" required>
                    @php
                        $roles = ['user' => 'User', 'volunteer' => 'Volunteer', 'official' => 'Official', 'admin' => 'Admin'];
                    @endphp
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $user->role ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

        <div class="flex gap-6 items-center mt-2">
            <label class="cursor-pointer label">
                <input type="checkbox" name="is_verified" class="checkbox" value="1" @checked(old('is_verified', $user->is_verified ?? false))>
                <span class="label-text ml-2">Verified</span>
            </label>

            <label class="cursor-pointer label">
                <input type="checkbox" name="is_active" class="checkbox" value="1" @checked(old('is_active', $user->is_active ?? true))>
                <span class="label-text ml-2">Active</span>
            </label>
        </div>

        <div>
            <label class="block mb-1 font-semibold" for="reputation_score">Reputation Score</label>
            <input type="number" name="reputation_score" id="reputation_score"
            value="{{ old('reputation_score', $user->reputation_score ?? '') }}" class="input input-bordered w-full" min="0" max="10000">
            @error('reputation_score')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="btn btn-primary w-full">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
        </div>
        </form>
    </div>
</x-app-layout>
