@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 py-10">

        <a href="{{ route('manager.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-700 mb-6">
            ← Kembali ke Dashboard
        </a>

        @if($task->status === 'approved')
        <div class="rounded-xl border border-yellow-300 bg-yellow-50 px-5 py-4 text-sm text-yellow-800 mb-4">
            ⚠️ Task ini sudah di-approve dan tidak dapat diedit.
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100"
                 style="background: linear-gradient(135deg, #780000 0%, #9a0000 100%)">
                <h2 class="text-lg font-bold text-white">Edit Tugas</h2>
                <p class="text-sm text-red-200 mt-0.5">Proyek: {{ $task->project->project_name ?? '-' }}</p>
            </div>

            <form method="POST" action="{{ route('manager.task.update', $task) }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                @if ($errors->any())
                <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tugas</label>
                    <input type="text" name="task_name"
                           value="{{ old('task_name', $task->task_name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                           {{ $task->status === 'approved' ? 'disabled' : '' }} required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                              {{ $task->status === 'approved' ? 'disabled' : '' }}>{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" name="due_date"
                               value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                               {{ $task->status === 'approved' ? 'disabled' : '' }} required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan</label>
                        <select name="difficulty"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                                {{ $task->status === 'approved' ? 'disabled' : '' }}>
                            <option value="Low"    {{ old('difficulty', $task->difficulty) === 'Low'    ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('difficulty', $task->difficulty) === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High"   {{ old('difficulty', $task->difficulty) === 'High'   ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Developer</label>
                    <select name="developer_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                            {{ $task->status === 'approved' ? 'disabled' : '' }}>
                        @foreach($developers as $dev)
                            <option value="{{ $dev->id }}"
                                {{ old('developer_id', $task->developer_id) == $dev->id ? 'selected' : '' }}>
                                {{ $dev->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($task->status !== 'approved')
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background-color:#780000">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('manager.dashboard') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200">
                        Batal
                    </a>
                </div>
                @endif
            </form>
        </div>

    </div>
</div>
@endsection