@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <div class="max-w-4xl mx-auto p-4 md:p-6">
        <!-- Back Button -->
        <a href="{{ route('developer.dashboard') }}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 mb-6 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>

        <!-- Task Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ ucfirst($task->status) }}</span>
                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded">{{ $task->project->project_name }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $task->task_name }}</h1>
                    <div class="text-sm text-gray-600">Dibuat: {{ $task->created_at->translatedFormat('j F Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Deadline</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $task->due_date->translatedFormat('j F Y') }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Deskripsi Tugas</h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {{ $task->description ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>

                <!-- Task Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Tugas</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Status</div>
                            <div class="font-semibold text-gray-900 capitalize">{{ $task->status }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Project</div>
                            <div class="font-semibold text-gray-900">{{ $task->project->project_name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Deadline</div>
                            <div class="font-semibold text-gray-900">{{ $task->due_date->translatedFormat('j F Y') }}</div>
                        </div>
                        @if($task->completed_at)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Selesai</div>
                                <div class="font-semibold text-green-600">{{ $task->completed_at->translatedFormat('j F Y H:i') }}</div>
                            </div>
                        @endif
                        @if($task->pm_rating)
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Rating</div>
                                <div class="font-semibold text-gray-900">{!! str_repeat('★', $task->pm_rating) . str_repeat('☆', 5-$task->pm_rating) !!} ({{ $task->pm_rating }}/5)</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">1</div>
                                <div class="w-1 h-12 bg-gray-200 my-2"></div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Tugas Dibuat</div>
                                <div class="text-sm text-gray-600">{{ $task->created_at->translatedFormat('j F Y H:i') }}</div>
                            </div>
                        </div>
                        @if($task->status !== 'todo')
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full {{ $task->status === 'in_progress' || $task->status === 'completed' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center font-bold">2</div>
                                    <div class="w-1 h-12 {{ $task->status === 'completed' ? 'bg-green-200' : 'bg-gray-200' }} my-2"></div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Mulai Dikerjakan</div>
                                    <div class="text-sm text-gray-600">Sedang berjalan...</div>
                                </div>
                            </div>
                        @endif
                        @if($task->status === 'completed')
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Selesai</div>
                                    <div class="text-sm text-gray-600">{{ $task->completed_at->translatedFormat('j F Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Project Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Project</h3>
                    <div class="font-semibold text-gray-900 mb-2">{{ $task->project->project_name }}</div>
                    <div class="text-sm text-gray-600 mb-4">{{ $task->project->description ?? 'Tidak ada deskripsi' }}</div>
                    <div class="text-sm text-gray-700">
                        <strong>Manager:</strong> {{ $task->project->manager->name }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Aksi</h3>
                    <div class="space-y-3">
                        @if($task->status === 'todo')
                            <form action="{{ route('tasks.start', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                    🚀 Mulai Mengerjakan
                                </button>
                            </form>
                        @elseif($task->status === 'in_progress')
                            <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                    ✅ Tandai Selesai
                                </button>
                            </form>
                        @else
                            <div class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-center font-semibold">
                                ✓ Sudah Selesai
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alert -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <div class="text-green-600 font-bold text-xl">✓</div>
                            <div>
                                <div class="font-semibold text-green-900">Berhasil!</div>
                                <div class="text-sm text-green-700">{{ session('success') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
