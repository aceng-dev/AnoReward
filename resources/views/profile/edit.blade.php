<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">            @if($user->role === 'developer')
                <div class="p-6 bg-white shadow sm:rounded-2xl">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
                        <div class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Profil Developer</p>
                                    <h1 class="mt-2 text-2xl font-semibold text-slate-900">{{ $user->name }}</h1>
                                    <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
                                </div>
                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700 capitalize">{{ $user->role }}</span>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-white p-5 shadow-sm">
                                    <p class="text-sm text-slate-500">Tugas sedang dikerjakan</p>
                                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $developerStats['activeTasks'] ?? 0 }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-5 shadow-sm">
                                    <p class="text-sm text-slate-500">Tugas selesai</p>
                                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $developerStats['completedTasks'] ?? 0 }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-5 shadow-sm">
                                    <p class="text-sm text-slate-500">Total poin</p>
                                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($user->total_points ?? 0) }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-5 shadow-sm">
                                    <p class="text-sm text-slate-500">Rekomendasi gaji</p>
                                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $developerStats['salaryRecommendations'] ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <a href="{{ route('tasks.index') }}" class="block rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Lihat Tugas Saya</a>
                                <a href="{{ route('developer.statistics') }}" class="block rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Statistik Developer</a>
                                <a href="{{ route('developer.rewards.index') }}" class="block rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Reward Saya</a>
                            </div>
                        </div>

                        <div class="w-full lg:w-80 rounded-2xl bg-blue-600 p-6 text-white shadow-lg">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-100/80">Detail Akun</p>
                            <div class="mt-5 space-y-4">
                                <div class="rounded-2xl bg-blue-500/10 p-4">
                                    <p class="text-sm text-blue-100/80">Email</p>
                                    <p class="mt-2 font-semibold">{{ $user->email }}</p>
                                </div>
                                <div class="rounded-2xl bg-blue-500/10 p-4">
                                    <p class="text-sm text-blue-100/80">Role</p>
                                    <p class="mt-2 font-semibold capitalize">{{ $user->role }}</p>
                                </div>
                                <div class="rounded-2xl bg-blue-500/10 p-4">
                                    <p class="text-sm text-blue-100/80">Gaji saat ini</p>
                                    <p class="mt-2 font-semibold">Rp{{ number_format($user->current_salary ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl bg-blue-500/10 p-4">
                                <p class="text-sm text-blue-100/80">Tentang Halaman</p>
                                <p class="mt-3 text-sm leading-6 text-blue-100/90">Kelola informasi akun Anda, perbarui data profil, dan lihat ringkasan performa developer di satu halaman.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
