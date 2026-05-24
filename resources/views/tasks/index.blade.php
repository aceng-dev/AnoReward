@extends('layouts.app')

@section('content')
<style>
    .filter-pill {
        transition: transform .2s ease, background-color .2s ease, color .2s ease, box-shadow .2s ease;
    }
    .filter-pill:hover {
        transform: translateY(-1px);
    }
    .filter-pill:active {
        transform: translateY(1px);
        box-shadow: inset 0 4px 8px rgba(15, 23, 42, .12);
    }
    .filter-pill:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
    }
    .filter-pill.active-pill {
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    }
</style>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Daftar Task</h1>
            <a href="{{ route('developer.dashboard') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
        </div>

        <!-- Filter -->
        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <div class="flex flex-wrap gap-4">
                <a href="?status=" class="filter-pill px-4 py-2 rounded-full text-sm font-medium {{ empty(request('status')) ? 'active-pill bg-gray-300 text-gray-900 shadow-sm' : 'bg-gray-100 text-gray-700' }} hover:bg-gray-200">
                    Semua ({{ $taskCounts['all'] }})
                </a>
                <a href="?status=todo" class="filter-pill px-4 py-2 rounded-full text-sm font-medium {{ request('status') === 'todo' ? 'active-pill bg-purple-200 text-purple-900 shadow-sm' : 'bg-gray-100 text-gray-700' }} hover:bg-gray-200">
                    Belum Dimulai ({{ $taskCounts['todo'] }})
                </a>
                <a href="?status=inprogress" class="filter-pill px-4 py-2 rounded-full text-sm font-medium {{ request('status') === 'inprogress' ? 'active-pill bg-blue-200 text-blue-900 shadow-sm' : 'bg-gray-100 text-gray-700' }} hover:bg-gray-200">
                    Sedang Dikerjakan ({{ $taskCounts['inprogress'] }})
                </a>
                <a href="?status=done" class="filter-pill px-4 py-2 rounded-full text-sm font-medium {{ request('status') === 'done' ? 'active-pill bg-green-200 text-green-900 shadow-sm' : 'bg-gray-100 text-gray-700' }} hover:bg-gray-200">
                    Selesai ({{ $taskCounts['done'] }})
                </a>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($tasks->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-900">
                                        {{ $task->title }}
                                    </a>
                                    <p class="text-gray-600 mt-1">{{ $task->project->project_name ?? $task->project->name ?? 'Tanpa Project' }}</p>
                                </div>
                                <div class="text-right">
                                    @if($task->status === 'todo')
                                        <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded">Belum Dimulai</span>
                                    @elseif($task->status === 'inprogress')
                                        <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">Sedang Dikerjakan</span>
                                    @elseif($task->status === 'done')
                                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">{{ ucfirst($task->status) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Deadline</p>
                                    <p class="font-semibold text-gray-900">{{ optional($task->deadline)->format('d M Y') ?? 'Tanpa deadline' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Poin</p>
                                    <p class="font-semibold text-gray-900">{{ $task->points ?? 100 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Priority</p>
                                    <p class="font-semibold text-gray-900">{{ $task->priority ?? 'Medium' }}</p>
                                </div>
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-sm px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Lihat</a>
                                    @if($task->status === 'todo')
                                        <form action="{{ route('tasks.start', $task) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Mulai</button>
                                        </form>
                                    @elseif($task->status === 'inprogress')
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Selesai</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg">Tidak ada task</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
