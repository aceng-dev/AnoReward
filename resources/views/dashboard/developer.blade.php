@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
Carbon::setLocale('id');
$today = Carbon::now();

// Dummy user (fallback if auth not available)
$user = auth()->user() ?? (object) ['name' => 'Budi', 'role' => 'Developer'];

// Dummy tasks
$tasks = [
    ['project' => 'Alpha', 'title' => 'Fix login bug', 'priority' => 'High', 'deadline' => Carbon::now()->addDays(2), 'status' => 'in_progress', 'points' => 150],
    ['project' => 'Beta', 'title' => 'Implement payment gateway', 'priority' => 'Medium', 'deadline' => Carbon::now()->addDays(5), 'status' => 'todo', 'points' => 300],
    ['project' => 'Gamma', 'title' => 'Refactor API endpoints', 'priority' => 'Low', 'deadline' => Carbon::now()->addDays(7), 'status' => 'todo', 'points' => 100],
];

$completed = [
    ['title' => 'Database Design', 'completed_at' => Carbon::create(2024,5,18), 'points' => 150, 'rating' => 4, 'manager' => 'Ani', 'bonus' => ['status' => 'Menunggu approval admin', 'amount' => 250000]],
    ['title' => 'Auth Flow', 'completed_at' => Carbon::create(2024,5,15), 'points' => 200, 'rating' => 5, 'manager' => 'Budi', 'bonus' => ['status' => 'Approved', 'amount' => 500000]],
];

$leaderboard = [
    ['rank' => 1, 'name' => 'Rina', 'points' => 4200, 'percent' => 92],
    ['rank' => 2, 'name' => 'Budi', 'points' => 2450, 'percent' => 78], // current user
    ['rank' => 3, 'name' => 'Sinta', 'points' => 1900, 'percent' => 60],
    ['rank' => 4, 'name' => 'Ari', 'points' => 1200, 'percent' => 38],
    ['rank' => 5, 'name' => 'Dedi', 'points' => 950, 'percent' => 24],
];

$rewards = [
    ['month' => 'Mei 2024', 'amount' => 500000, 'status' => 'Approved'],
    ['month' => 'April 2024', 'amount' => 250000, 'status' => 'Approved'],
];

// Stats
$activeCount = collect($tasks)->whereIn('status', ['todo','in_progress'])->count();
$doneCount = collect($completed)->count();
$totalPoints = collect($completed)->sum('points');
$ranking = '#2';

function priorityColor($p) {
    if ($p === 'High') return ['border' => 'border-red-500', 'bg' => 'bg-red-50', 'text' => 'text-red-700', 'badge' => 'bg-red-100'];
    if ($p === 'Medium') return ['border' => 'border-yellow-400', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'badge' => 'bg-yellow-100'];
    return ['border' => 'border-green-500', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'badge' => 'bg-green-100'];
}

function statusBadge($status) {
    if ($status === 'in_progress') return ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Sedang Dikerjakan'];
    return ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Belum Dimulai'];
}

@endphp

<style>
    .stat-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .task-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .task-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #780000, #a50000);
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(120,0,0,0.3);
    }
    
    .btn-secondary {
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        transform: translateY(-2px);
        background: #f3f4f6;
    }
    
    .leaderboard-item {
        transition: all 0.3s ease;
    }
    .leaderboard-item:hover {
        background: #f9fafb;
        padding-left: 8px;
    }
    
    .page-title {
        background: linear-gradient(135deg, #780000 0%, #a50000 100%);
        padding: 2rem;
        border-radius: 0.75rem;
        color: white;
        margin-bottom: 2rem;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <div class="max-w-7xl mx-auto p-4 md:p-6">
        <!-- Page Title Section -->
        <div class="page-title shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">Dashboard Developer</h1>
                    <p class="text-red-100">Kelola tugas dan pantau progress pekerjaan Anda</p>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    <div class="text-lg font-semibold">{{ $user->name }}</div>
                    <div class="text-sm text-red-100">{{ $today->translatedFormat('l, j F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tugas Aktif</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $activeCount }}</div>
                        <div class="text-xs text-gray-400 mt-2">sedang berjalan</div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Selesai</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $doneCount }}</div>
                        <div class="text-xs text-gray-400 mt-2">bulan ini</div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Poin</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalPoints,0,',','.') }}</div>
                        <div class="text-xs text-gray-400 mt-2">reward diperoleh</div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Peringkat</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $ranking }}</div>
                        <div class="text-xs text-gray-400 mt-2">dari 50 developer</div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" viewBox="0 0 20 20" fill="currentColor"><path d="M6 16.235V7a2 2 0 012-2h8a2 2 0 012 2v9.235A.75.75 0 0116.75 17H3.25a.75.75 0 010-1.5zM13 11.75h-2v2h2v-2zm-4-2h2v2H9v-2zm4-2h-2v2h2V7.75z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tasks Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tasks Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                        <span class="h-6 w-1 bg-gradient-to-b from-red-600 to-red-800 rounded"></span>
                        <span>Tugas Saya</span>
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">{{ $activeCount }} tugas aktif menunggu penyelesaian</p>
                </div>

                <!-- Tasks by Priority -->
                @foreach (['High','Medium','Low'] as $prio)
                    @php $color = priorityColor($prio); @endphp
                    @php $tasks_by_prio = collect($tasks)->where('priority',$prio); @endphp
                    @if($tasks_by_prio->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="px-3 py-1 rounded-full {{ $color['badge'] }} {{ $color['text'] }} text-xs font-semibold">
                                    @if($prio === 'High') 🔴 PRIORITAS TINGGI @elseif($prio === 'Medium') 🟡 PRIORITAS SEDANG @else 🟢 PRIORITAS RENDAH @endif
                                </div>
                                <span class="text-sm text-gray-500">({{ $tasks_by_prio->count() }} tugas)</span>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($tasks_by_prio as $t)
                                    @php 
                                        $status = statusBadge($t['status']);
                                        $daysLeft = $t['deadline']->diffInDays(Carbon::now());
                                        $urgency = $daysLeft <= 1 ? 'critical' : ($daysLeft <= 3 ? 'warning' : 'normal');
                                    @endphp
                                    <div class="task-card p-4 rounded-lg border {{ $color['border'] }} {{ $color['bg'] }}">
                                        <div class="flex flex-col gap-3">
                                            <!-- Task Header -->
                                            <div>
                                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                    <span class="inline-block px-2 py-0.5 text-xs rounded {{ $status['bg'] }} {{ $status['text'] }} font-semibold">
                                                        {{ $status['label'] }}
                                                    </span>
                                                    <span class="text-xs font-medium text-gray-600 bg-white bg-opacity-60 px-2 py-0.5 rounded">
                                                        {{ $t['project'] }}
                                                    </span>
                                                    @if($urgency === 'critical')
                                                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded">⚠️ Segera!</span>
                                                    @endif
                                                </div>
                                                <h3 class="font-semibold text-gray-900">{{ $t['title'] }}</h3>
                                            </div>

                                            <!-- Task Info -->
                                            <div class="flex flex-wrap gap-4 text-sm">
                                                <div class="flex items-center gap-1 {{ $urgency === 'critical' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V6a4 4 0 018 0v1m-2 6h-4"/></svg>
                                                    <span>{{ $t['deadline']->translatedFormat('j F') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 2.239-7 5v2h14v-2c0-2.761-3.134-5-7-5z"/></svg>
                                                    <span>+{{ $t['points'] }} poin</span>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="flex gap-2 pt-2">
                                                <button class="flex-1 flex items-center justify-center gap-1 px-3 py-2 rounded-lg btn-primary text-white text-sm font-medium whitespace-nowrap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                                    Mulai
                                                </button>
                                                <button class="px-3 py-2 rounded-lg btn-secondary text-gray-700 text-sm font-medium border border-gray-200 hover:bg-gray-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Sidebar -->
            <div class="space-y-5">
                <!-- Recently Completed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <span>✅ Baru Selesai</span>
                    </h3>
                    <div class="space-y-2">
                        @forelse($completed as $c)
                            <div class="p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 hover:shadow-md transition">
                                <div class="flex justify-between items-start gap-2 mb-1">
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ $c['title'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $c['completed_at']->translatedFormat('j F') }}</div>
                                    </div>
                                    <div class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded whitespace-nowrap">+{{ $c['points'] }}</div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <div>Rating: {!! str_repeat('★', $c['rating']) . str_repeat('☆', 5-$c['rating']) !!} ({{ $c['rating'] }}/5)</div>
                                    <div class="font-semibold text-green-600">💰 Rp {{ number_format($c['bonus']['amount'],0,',','.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4 text-sm">Belum ada</div>
                        @endforelse
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">🏆 Top Developer</h3>
                    <div class="space-y-2">
                        @foreach($leaderboard as $row)
                            <div class="leaderboard-item flex items-center justify-between p-2 rounded {{ $row['name'] === $user->name ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50' }}">
                                <div class="flex items-center gap-2 flex-1">
                                    <div class="text-sm font-bold w-6 text-center">
                                        @if($row['rank']==1) 🥇 @elseif($row['rank']==2) 🥈 @elseif($row['rank']==3) 🥉 @else #{{ $row['rank'] }} @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 text-sm">{{ $row['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $row['points'] }} pts</div>
                                    </div>
                                    @if($row['name'] === $user->name)
                                        <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded font-semibold">YOU</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Rewards Summary -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-sm border border-amber-200 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">💰 Reward</h3>
                    <div class="text-3xl font-bold text-amber-600 mb-3">
                        {{ number_format(collect($leaderboard)->firstWhere('name',$user->name)['points'] ?? 2450,0,',','.') }}
                        <span class="text-lg font-semibold ml-1">pts</span>
                    </div>
                    <button class="w-full btn-primary text-white py-2 rounded-lg mb-3 font-semibold text-sm">
                        🎁 Tukar Hadiah
                    </button>
                    <div class="text-xs font-semibold text-gray-700 mb-2">Riwayat:</div>
                    <div class="space-y-1">
                        @foreach($rewards as $r)
                            <div class="flex items-center justify-between p-2 bg-white bg-opacity-60 rounded text-xs">
                                <span class="text-gray-700">{{ $r['month'] }}</span>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">Rp {{ number_format($r['amount'],0,',','.') }}</div>
                                    <div class="text-green-600">{{ $r['status'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection