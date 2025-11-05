<x-guest-layout>
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-center">
        <div class="hidden lg:flex flex-col justify-center items-center w-1/2 h-screen px-12relative">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
            <div class="relative text-center max-w-md fade-in">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Join SalbaKitaPH 🚀</h1>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Create your account to help build safer communities.
                    Together, we can make a difference.
                </p>
            </div>
        </div>

        <div class="flex flex-col justify-center items-center w-full lg:w-1/2 h-full px-8 lg:px-12 fade-in text-center">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-700 mb-1">SalbaKitaPH</a>
            <p class="text-gray-500 mb-4 text-sm">Your safety companion — anytime, anywhere.</p>

            <div class="card w-full max-w-md bg-white border border-gray-200 shadow-sm rounded-md">
                <div class="card-body p-7">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4 text-left">
                        @csrf

                        <div class="flex justify-between items-center gap-4">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" class="text-gray-700 font-medium" />
                                <x-text-input id="first_name"
                                            class="input input-bordered w-full mt-1"
                                            type="text" name="first_name"
                                            :value="old('first_name')" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-red-500 text-sm" />
                            </div>

                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" class="text-gray-700 font-medium" />
                                <x-text-input id="last_name"
                                            class="input input-bordered w-full mt-1"
                                            type="text" name="last_name"
                                            :value="old('last_name')" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-red-500 text-sm" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                            <x-text-input id="email"
                                          class="input input-bordered w-full mt-1"
                                          type="email" name="email"
                                          :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm" />
                        </div>

                        <div class="flex justify-between items-center gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                                <x-text-input id="password"
                                            class="input input-bordered w-full mt-1"
                                            type="password" name="password"
                                            required />
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
                                <x-text-input id="password_confirmation"
                                            class="input input-bordered w-full mt-1"
                                            type="password" name="password_confirmation"
                                            required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm" />
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="btn w-full bg-blue-600 hover:bg-blue-700 text-white normal-case font-medium tracking-wide border-none shadow-sm transition-transform hover:scale-[1.02]">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="mt-3 text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                    Log in here
                </a>
            </p>
        </div>
    </div>

    <style>
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
    </style>
</x-guest-layout>
