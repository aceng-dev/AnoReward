@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
Carbon::setLocale('id');
$today = Carbon::now();

$statusClasses = [
    'todo' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Belum Dimulai'],
    'inprogress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Sedang Dikerjakan'],
    'done' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
];

function formatDate($date) {
    return $date ? Carbon::parse($date)->translatedFormat('j F Y') : 'Tanpa deadline';
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
        <div class="page-title shadow-lg">
            <div class="flex flex-col gap-4">
                <div>
                    <div class="text-3xl font-bold">Dashboard Developer</div>
                    <div class="text-sm text-red-100 mt-2">Halo, {{ $developer->name }} — {{ $today->translatedFormat('l, j F Y') }}</div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('developer.statistics') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-100 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="currentColor"><path d="M3 13h4V3H3v10zM13 3v10h4V3h-4zM8 17h4v-4H8v4z"/></svg>
                        <div class="text-sm font-semibold text-gray-900">Statistik</div>
                    </a>

                    <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-100 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v12a1 1 0 001 1h8a1 1 0 001-1V3a1 1 0 00-1-1H6zM4 6h12v2H4V6z"/></svg>
                        <div class="text-sm font-semibold text-gray-900">Tasks</div>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">{{ $stats['active_tasks'] }}</span>
                    </a>

                    <a href="{{ route('leaderboard.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-100 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a1 1 0 00-1 1v14a1 1 0 002 0V3a1 1 0 00-1-1zM4 6a1 1 0 011-1h.01a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V6zm12-1h.01A1 1 0 0118 6v8a1 1 0 01-1 1h-.01a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                        <div class="text-sm font-semibold text-gray-900">Leaderboard</div>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">{{ $totalDevelopers }}</span>
                    </a>

                    <a href="{{ route('developer.rewards.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg btn-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <div class="text-sm font-semibold">Reward</div>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tugas Aktif</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['active_tasks'] }}</div>
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
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tugas Selesai</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] }}</div>
                        <div class="text-xs text-gray-400 mt-2">total selesai</div>
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
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_points'],0,',','.') }}</div>
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
                        <div class="text-2xl font-bold text-gray-900">{{ $currentRank }}</div>
                        <div class="text-xs text-gray-400 mt-2">dari {{ $totalDevelopers }} developer</div>
                    </div>
                    <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" viewBox="0 0 20 20" fill="currentColor"><path d="M6 16.235V7a2 2 0 012-2h8a2 2 0 012 2v9.235A.75.75 0 0116.75 17H3.25a.75.75 0 010-1.5zM13 11.75h-2v2h2v-2zm-4-2h2v2H9v-2zm4-2h-2v2h2V7.75z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                        <span class="h-6 w-1 bg-gradient-to-b from-red-600 to-red-800 rounded"></span>
                        <span>Tugas Saya</span>
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">{{ $activeTasks->count() }} tugas aktif menunggu penyelesaian</p>
                </div>

                @if($activeTasks->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <div class="space-y-4">
                            @foreach($activeTasks as $task)
                                @php
                                    $meta = $statusClasses[$task->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($task->status)];
                                    $dueDate = $task->deadline;
                                    $daysLeft = $dueDate ? $dueDate->diffInDays(Carbon::now(), false) : null;
                                    $urgency = $daysLeft !== null ? ($daysLeft < 0 ? 'overdue' : ($daysLeft <= 1 ? 'critical' : ($daysLeft <= 3 ? 'warning' : 'normal'))) : 'normal';
                                @endphp
                                <div class="task-card p-4 rounded-lg border {{ $meta['bg'] }} {{ $meta['text'] }}">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap gap-2 items-center mb-3">
                                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $meta['bg'] }} {{ $meta['text'] }} font-semibold text-xs">
                                                        {{ $meta['label'] }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 bg-white bg-opacity-70 px-2 py-1 rounded">{{ $task->project->project_name ?? 'Tanpa Project' }}</span>
                                                    @if($urgency === 'critical')
                                                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">?? Segera</span>
                                                    @elseif($urgency === 'overdue')
                                                        <span class="text-xs font-bold text-white bg-red-600 px-2 py-1 rounded">?? Terlambat</span>
                                                    @endif
                                                </div>
                                                <h3 class="text-xl font-semibold text-gray-900">{{ $task->title }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">Deadline: {{ $dueDate ? $dueDate->translatedFormat('j F Y') : 'Tanpa deadline' }}</p>
                                            </div>
                                            <div class="flex flex-col items-start gap-3">
                                                <div class="text-xs text-gray-500 uppercase tracking-wide">Poin</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ $task->points ?? 100 }}</div>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-3">
                                            <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                                                Lihat Task
                                            </a>
                                            @if($task->status === 'todo')
                                                <form action="{{ route('tasks.start', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg btn-primary text-white text-sm font-medium">Mulai Mengerjakan</button>
                                                </form>
                                            @elseif($task->status === 'inprogress')
                                                <button type="button"
                                                    onclick="openCompleteModal({{ $task->id }}, '{{ addslashes($task->task_name) }}')"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                                                    Tandai Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center text-gray-600">
                        Belum ada tugas aktif saat ini. Silakan cek daftar tugas atau minta update dari manajer.
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">? Baru Selesai</h3>
                    <div class="space-y-3">
                        @forelse($completedTasks->take(5) as $task)
                            <div class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $task->completed_at?->translatedFormat('j F Y') ?? 'Tanpa tanggal' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">+{{ $task->points ?? 100 }} pts</div>
                                        <div class="text-xs text-gray-500">{{ $task->pm_rating ? $task->pm_rating . '/5' : 'Belum rating' }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4 text-sm">Belum ada task selesai.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">?? Top Developer</h3>
                    <div class="space-y-2">
                        @foreach($leaderboard as $userRow)
                            <div class="leaderboard-item flex items-center justify-between p-3 rounded-lg border {{ $userRow->id === $developer->id ? 'border-blue-200 bg-blue-50' : 'border-gray-100 hover:bg-gray-50' }}">
                                <div class="flex items-center gap-3">
                                    <div class="text-sm font-bold w-7 text-center">
                                        @if($userRow->rank === 1) ?? @elseif($userRow->rank === 2) ?? @elseif($userRow->rank === 3) ?? @else #{{ $userRow->rank }} @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ $userRow->name }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($userRow->total_points,0,',','.') }} pts</div>
                                    </div>
                                </div>
                                @if($userRow->id === $developer->id)
                                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Anda</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-sm border border-amber-200 p-5">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">?? Reward</h3>
                    <div class="text-3xl font-bold text-amber-600 mb-3">{{ number_format($stats['total_points'],0,',','.') }} pts</div>
                    <a href="{{ route('developer.rewards.index') }}" class="block w-full text-center btn-primary text-white py-2 rounded-lg mb-4">Lihat Reward Saya</a>
                    <div class="text-xs font-semibold text-gray-700 mb-2">Riwayat reward terbaru</div>
                    <div class="space-y-2">
                        @forelse($rewards as $reward)
                            <div class="rounded-xl bg-white p-3 border border-amber-100 text-xs text-gray-700">
                                <div class="font-semibold">{{ $reward->period_month?->translatedFormat('F Y') ?? $reward->created_at->translatedFormat('F Y') }}</div>
                                <div class="flex justify-between gap-2 mt-1">
                                    <span>Rp {{ number_format($reward->amount,0,',','.') }}</span>
                                    <span class="capitalize {{ $reward->status === 'approved' ? 'text-green-700' : ($reward->status === 'pending' ? 'text-yellow-700' : 'text-red-700') }}">{{ $reward->status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4 text-sm">Belum ada reward.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
function openCompleteModal(taskId, taskName) {
    document.getElementById('modal-task-name').textContent = taskName;
    document.getElementById('complete-form').action = '/tasks/' + taskId + '/complete';
    document.getElementById('complete-modal').classList.remove('hidden');
    document.getElementById('repo_link').value = '';
    document.getElementById('repo_link').focus();
}
function closeCompleteModal() {
    document.getElementById('complete-modal').classList.add('hidden');
}
</script>
@endpush

@push('modals')
{{-- Modal Tandai Selesai --}}
<div id="complete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Tandai Tugas Selesai</h3>
        <p class="text-sm text-gray-500 mb-4">
            Tugas: <span id="modal-task-name" class="font-semibold text-gray-700"></span>
        </p>

        <form id="complete-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Link Repository / Bukti Kerja <span class="text-red-500">*</span>
                </label>
                <input type="url" name="repo_link" id="repo_link"
                    placeholder="https://github.com/username/repo"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600"
                    required>
                <p class="text-xs text-gray-400 mt-1">Wajib diisi. Masukkan URL repo atau link bukti kerja.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700">
                    Konfirmasi Selesai
                </button>
                <button type="button" onclick="closeCompleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endpush