@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 py-10">

        <a href="{{ route('manager.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-700 mb-6">
            ← Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100"
                 style="background: linear-gradient(135deg, #780000 0%, #9a0000 100%)">
                <h2 class="text-lg font-bold text-white">Edit Proyek</h2>
                <p class="text-sm text-red-200 mt-0.5">Ubah informasi proyek yang sudah ada</p>
            </div>

            <form method="POST" action="{{ route('manager.project.update', $project) }}" class="p-6 space-y-5">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Proyek</label>
                    <input type="text" name="project_name"
                           value="{{ old('project_name', $project->project_name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Proyek</label>
                    <select name="status"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-700">
                        <option value="planning"  {{ $project->status === 'planning'  ? 'selected' : '' }}>Planning</option>
                        <option value="ongoing"   {{ $project->status === 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

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
            </form>
        </div>

        {{-- Zona Berbahaya --}}
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                <h3 class="text-sm font-bold text-red-700">Zona Berbahaya</h3>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700">Hapus Proyek Ini</p>
                    <p class="text-xs text-gray-500 mt-0.5">Semua task dalam proyek ini juga akan terhapus.</p>
                </div>
                <form method="POST" action="{{ route('manager.project.destroy', $project) }}"
                      onsubmit="return confirm('Yakin ingin menghapus proyek ini beserta semua task-nya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700">
                        Hapus Proyek
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection