<x-guest-layout>
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-center">
        <div class="hidden lg:flex flex-col justify-center items-center w-1/2 h-screen px-12relative">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
            <div class="relative text-center max-w-md fade-in">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome Back 👋</h1>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Stay alert. Stay safe.
                    Log in to continue connecting and reporting with
                    <a href="{{ route('home') }}" class="font-semibold text-blue-700 cursor-pointer">SalbaKitaPH</a>.
                </p>
            </div>
        </div>

        <div class="flex flex-col justify-center items-center w-full lg:w-1/2 p-10 lg:p-16 fade-in text-center">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-700 mb-1 cursor-pointer">SalbaKitaPH</a>
            <p class="text-gray-500 mb-4 text-sm">Your safety companion — anytime, anywhere.</p>

            <div class="card w-full max-w-sm bg-white border border-gray-200 shadow-sm rounded-md">
                <div class="card-body p-7">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                            <x-text-input id="email"
                                          class="input input-bordered w-full mt-1 focus:border-blue-500 focus:ring-blue-500"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                            <x-text-input id="password"
                                          class="input input-bordered w-full mt-1 focus:border-blue-500 focus:ring-blue-500"
                                          type="password"
                                          name="password"
                                          required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center space-x-2">
                                <input id="remember_me" type="checkbox" class="checkbox checkbox-sm checkbox-primary" name="remember">
                                <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="" class="text-sm text-blue-600 hover:text-blue-700 hover:underline">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="btn w-full bg-blue-600 hover:bg-blue-700 text-white normal-case font-medium tracking-wide border-none shadow-sm transition-transform hover:scale-[1.02]">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-600">
                No account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                    Create one
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
