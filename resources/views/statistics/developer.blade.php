@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Statistik Performa Anda</h1>
            <a href="{{ route('developer.dashboard') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
        </div>

        <!-- Task Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Task</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['tasks']['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['tasks']['completed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Sedang Dikerjakan</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['tasks']['in_progress'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Belum Dimulai</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['tasks']['todo'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Overdue</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['tasks']['overdue'] }}</p>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <p class="text-sm text-gray-600 mb-2">Tingkat Penyelesaian Task</p>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div class="bg-green-600 h-4 rounded-full" style="width: {{ $stats['tasks']['completion_rate'] }}%"></div>
            </div>
            <p class="text-right text-sm font-bold text-gray-900">{{ round($stats['tasks']['completion_rate']) }}%</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Points Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Statistik Poin</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-yellow-50 rounded">
                        <p class="text-sm text-gray-600">Total Poin</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['points']['total'] }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-600">Poin Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['points']['this_month'] }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded">
                        <p class="text-sm text-gray-600">Rata-rata Poin/Task</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['points']['average_per_task'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Rewards Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Statistik Reward</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 rounded">
                        <p class="text-sm text-gray-600">Reward Disetujui</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($stats['rewards']['approved_total'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $stats['rewards']['approved_count'] }} reward</p>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded">
                        <p class="text-sm text-gray-600">Reward Menunggu</p>
                        <p class="text-lg font-bold text-yellow-600">Rp {{ number_format($stats['rewards']['pending_total'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $stats['rewards']['pending_count'] }} reward</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-600">Rata-rata Reward</p>
                        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($stats['rewards']['average_reward'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Performance Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Performa</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-pink-50 rounded">
                        <p class="text-sm text-gray-600">Rating Rata-rata</p>
                        <p class="text-2xl font-bold text-pink-600">{{ $stats['performance']['average_rating'] }}/5</p>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded">
                        <p class="text-sm text-gray-600">Project Terbaik</p>
                        <p class="text-lg font-bold text-indigo-600">{{ $stats['performance']['top_project'] }}</p>
                    </div>
                    <div class="p-4 bg-cyan-50 rounded">
                        <p class="text-sm text-gray-600">Bulan Paling Sibuk</p>
                        <p class="text-lg font-bold text-cyan-600">{{ $stats['performance']['busy_month'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Ranking Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Ranking</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded">
                        <p class="text-sm text-gray-600">Ranking Anda</p>
                        <p class="text-3xl font-bold text-purple-600">#{{ $stats['ranking']['rank'] }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Total Developer</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['ranking']['total_developers'] }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-600">Persentil</p>
                        <p class="text-2xl font-bold text-blue-600">Top {{ $stats['ranking']['percentile'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
