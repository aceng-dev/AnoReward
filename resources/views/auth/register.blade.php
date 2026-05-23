<x-guest-layout>
    <div class="min-h-[85vh] flex flex-col justify-center items-center px-4 py-6">
        <!-- Card Container -->
        <div class="w-full sm:max-w-md bg-white border border-gray-100 p-8 rounded-2xl shadow-xl shadow-gray-200/50">

            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-[#1b1b18]">Create your account</h1>
                <p class="mt-2 text-sm text-[#4a4a4a]">Join AnoReward and start managing your rewards.</p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <x-text-input id="name" 
                        class="block w-full px-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        placeholder="John Doe" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <x-text-input id="email" 
                        class="block w-full px-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username" 
                        placeholder="name@company.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Role Selection (PENTING: Integrasi Multi-level Auth) -->
                <div>
                    <x-input-label for="role" :value="__('Register As (Role)')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <select id="role" 
                        name="role" 
                        required
                        class="block w-full px-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out shadow-sm">
                        <option value="" disabled selected>Select your role...</option>
                        <option value="developer" {{ old('role') == 'developer' ? 'selected' : '' }}>Developer / Team Member</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Project Manager</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <x-text-input id="password" 
                        class="block w-full px-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1" />
                    <x-text-input id="password_confirmation" 
                        class="block w-full px-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-[#780000] focus:ring focus:ring-[#780000]/20 transition duration-150 ease-in-out" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Action Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-[#780000] hover:bg-[#5f0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#780000]/50 active:scale-[0.98] transition-all duration-150">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>

            <!-- Footer (Link to Login) -->
            <div class="mt-6 text-center text-xs text-[#4a4a4a]">
                Already registered? 
                <a href="{{ route('login') }}" class="font-semibold text-[#780000] hover:underline">Log in</a>
            </div>

        </div>
    </div>
</x-guest-layout>