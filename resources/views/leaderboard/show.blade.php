@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Profil Developer</h1>
            <a href="{{ route('leaderboard.index') }}" class="text-blue-600 hover:text-blue-900">Kembali ke Leaderboard</a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left: Profile Info -->
                <div>
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $developer->name }}</h2>
                        <p class="text-gray-600">{{ $developer->email }}</p>
                    </div>

                    <div class="text-center p-4 bg-blue-50 rounded-lg mb-4">
                        <p class="text-sm text-gray-600">Ranking</p>
                        <p class="text-4xl font-bold text-blue-600">#{{ $rank }}</p>
                    </div>
                </div>

                <!-- Middle: Stats -->
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Total Task</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded">
                        <p class="text-sm text-gray-600">Task Selesai</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_tasks'] }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-600">Task Sedang Dikerjakan</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress_tasks'] }}</p>
                    </div>
                </div>

                <!-- Right: More Stats -->
                <div class="space-y-4">
                    <div class="p-4 bg-yellow-50 rounded">
                        <p class="text-sm text-gray-600">Total Poin</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['total_points'] }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded">
                        <p class="text-sm text-gray-600">Total Reward</p>
                        <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($stats['total_rewards'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-pink-50 rounded">
                        <p class="text-sm text-gray-600">Rating Rata-rata</p>
                        <p class="text-2xl font-bold text-pink-600">{{ round($stats['average_rating'], 1) }}/5</p>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Completion Rate</p>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-green-600 h-4 rounded-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                </div>
                <p class="text-right text-sm text-gray-600 mt-2">{{ round($stats['completion_rate']) }}%</p>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Task Selesai Terbaru</h2>
            </div>
            <div class="p-6">
                @if($completedTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($completedTasks as $task)
                            <div class="p-4 border border-gray-200 rounded hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $task->project->name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Selesai</span>
                                </div>
                                <div class="mt-3 flex justify-between text-sm text-gray-600">
                                    <span>{{ $task->completed_at->format('d M Y') }}</span>
                                    <span>{{ $task->points ?? 100 }} poin</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Tidak ada task selesai</p>
                @endif
            </div>
        </div>

        <!-- Rewards -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Reward yang Disetujui</h2>
            </div>
            <div class="p-6">
                @if($rewards->count() > 0)
                    <div class="space-y-3">
                        @foreach($rewards as $reward)
                            <div class="p-4 border border-gray-200 rounded hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $reward->period_month->format('F Y') }}</p>
                                        <p class="text-sm text-gray-600">{{ $reward->reason }}</p>
                                    </div>
                                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($reward->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Belum ada reward</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
