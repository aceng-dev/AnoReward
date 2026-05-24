@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Detail Reward</h1>
            <a href="{{ route('developer.rewards.index') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
        </div>

        <div class="bg-white rounded-lg shadow p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Bulan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $reward->period_month->format('F Y') }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Jumlah Reward</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($reward->amount, 0, ',', '.') }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Developer</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $developer->name }}</p>
                        <p class="text-sm text-gray-600">{{ $developer->email }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Manager/Pembuat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $manager->name ?? 'System' }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Status</p>
                        @if($reward->status === 'approved')
                            <div class="mt-2 p-4 bg-green-50 border border-green-200 rounded">
                                <p class="font-semibold text-green-700">✓ Disetujui</p>
                                <p class="text-sm text-green-600 mt-1">Disetujui pada: {{ $reward->approved_at->format('d F Y H:i') }}</p>
                            </div>
                        @elseif($reward->status === 'pending')
                            <div class="mt-2 p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="font-semibold text-yellow-700">⏳ Menunggu Persetujuan Admin</p>
                                <p class="text-sm text-yellow-600 mt-1">Dibuat pada: {{ $reward->created_at->format('d F Y H:i') }}</p>
                            </div>
                        @else
                            <div class="mt-2 p-4 bg-red-50 border border-red-200 rounded">
                                <p class="font-semibold text-red-700">✗ Ditolak</p>
                                <p class="text-sm text-red-600 mt-1">{{ $reward->rejection_reason }}</p>
                                <p class="text-sm text-red-600 mt-1">Ditolak pada: {{ $reward->rejected_at->format('d F Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Alasan/Deskripsi</p>
                        <p class="text-gray-900 mt-2">{{ $reward->reason }}</p>
                    </div>

                    @if($reward->task)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600">Task Terkait</p>
                            <div class="mt-2 p-4 bg-blue-50 border border-blue-200 rounded">
                                <p class="font-semibold text-gray-900">{{ $reward->task->title }}</p>
                                <p class="text-sm text-gray-600">{{ $reward->task->project->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
