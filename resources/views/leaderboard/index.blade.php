@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Leaderboard Developer</h1>
            <div class="flex gap-3">
                <a href="{{ route('leaderboard.statistics') }}" class="text-blue-600 hover:text-blue-900">Statistik</a>
                <a href="{{ route('developer.dashboard') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
            </div>
        </div>

        <!-- Filter & Sort -->
        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <div class="flex flex-wrap gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Urutkan Berdasarkan:</label>
                    <select onchange="window.location.href = '{{ route('leaderboard.index') }}?sort=' + this.value" class="mt-1 block px-3 py-2 border border-gray-300 rounded-md">
                        <option value="points" {{ request('sort') === 'points' ? 'selected' : '' }}>Poin Total</option>
                        <option value="tasks_completed" {{ request('sort') === 'tasks_completed' ? 'selected' : '' }}>Task Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <select onchange="window.location.href = '{{ route('leaderboard.index') }}?timeframe=' + this.value" class="mt-1 block px-3 py-2 border border-gray-300 rounded-md">
                        <option value="all" {{ request('timeframe') === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="month" {{ request('timeframe') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="week" {{ request('timeframe') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($developers->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Rank</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Poin</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Task Selesai</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Persentase</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($developers as $developer)
                            <tr class="hover:bg-gray-50 {{ $developer->id === auth()->id() ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="text-2xl font-bold text-gray-900">
                                        @if($developer->rank === 1)
                                            🥇
                                        @elseif($developer->rank === 2)
                                            🥈
                                        @elseif($developer->rank === 3)
                                            🥉
                                        @else
                                            #{{ $developer->rank }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $developer->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $developer->email }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $developer->total_points }}</td>
                                <td class="px-6 py-4">{{ $developer->completed_tasks }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($developer->percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ round($developer->percentage) }}%</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('leaderboard.show', $developer) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Lihat Profil
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg">Belum ada developer</p>
                </div>
            @endif
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
            Total Developer: {{ $totalDevelopers }}
        </div>

    </div>
</div>
@endsection
