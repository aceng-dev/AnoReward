@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Reward & Bonus</h1>
            <a href="{{ route('developer.dashboard') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Disetujui</p>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalApproved, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Menunggu Approval</p>
                <p class="text-3xl font-bold text-yellow-600">Rp {{ number_format($pendingApproval, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Reward</p>
                <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($totalRewards, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Rewards List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($rewards->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Bulan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Alasan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rewards as $reward)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $reward->period_month->format('F Y') }}</td>
                                <td class="px-6 py-4 font-semibold">Rp {{ number_format($reward->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($reward->reason, 50) }}</td>
                                <td class="px-6 py-4">
                                    @if($reward->status === 'approved')
                                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Disetujui</span>
                                    @elseif($reward->status === 'pending')
                                        <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded">Menunggu</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('developer.rewards.show', $reward) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg">Belum ada reward</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
