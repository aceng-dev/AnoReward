<x-guest-layout>
    <div class="min-h-[80vh] flex flex-col justify-center items-center px-4">
        <!-- Card Container -->
        <div class="w-full sm:max-w-md bg-white border border-gray-100 p-8 rounded-2xl shadow-xl shadow-gray-200/50">
            
            <!-- Session Status (Notifikasi jika ada) -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-[#1b1b18]">Welcome back</h1>
                <p class="mt-2 text-sm text-[#4a4a4a]">Log in to access your dashboard and rewards.</p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <div class="relative mt-1">
                        <x-text-input id="email" 
                            class="block w-full px-4 py-3 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="username" 
                            placeholder="name@company.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <div class="relative mt-1">
                        <x-text-input id="password" 
                            class="block w-full px-4 py-3 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password" 
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
                </div>

                <!-- Remember Me & Forgot Password Group -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#780000] shadow-sm focus:ring-[#780000]/30 focus:ring-offset-0" name="remember">
                        <span class="ms-2 text-xs font-medium text-[#4a4a4a] select-none">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-[#780000] hover:text-[#5f0000] hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-[#780000]/30" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-[#780000] hover:bg-[#5f0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#780000]/50 active:scale-[0.98] transition-all duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <!-- Optional Footer (Bagus untuk UX) -->
            <div class="mt-6 text-center text-xs text-[#4a4a4a]">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-semibold text-[#780000] hover:underline">Sign up</a>
            </div>

        </div>
    </div>
</x-guest-layout>