@extends('layouts.app')

@section('content')

<style>
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .page-title {
        background: linear-gradient(135deg, #780000 0%, #a50000 100%);
        padding: 2rem; border-radius: 0.75rem; color: white; margin-bottom: 2rem;
    }
    .section-title-bar {
        width: 4px; height: 20px;
        background: linear-gradient(to bottom, #780000, #a50000);
        border-radius: 2px; display: inline-block; margin-right: 8px; vertical-align: middle;
    }
</style>

<div class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto p-4 md:p-6">

    {{-- Page Header --}}
    <div class="page-title shadow-lg">
        <div class="text-3xl font-bold">Executive Dashboard</div>
        <div class="text-sm text-red-100 mt-2">{{ now()->translatedFormat('l, j F Y') }}</div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100" style="border-top: 3px solid #378ADD;">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Developer</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalDeveloper }}</div>
                    <div class="text-xs text-gray-400 mt-2">aktif dalam tim</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100" style="border-top: 3px solid #3B6D11;">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Approved Tasks</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $approvedTasks }}</div>
                    <div class="text-xs text-gray-400 mt-2">tugas disetujui</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card p-5 bg-white rounded-xl shadow-sm border border-gray-100" style="border-top: 3px solid #BA7517;">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Average Productivity</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($averageProductivity, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-2">rata-rata skor tim</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Top Performers --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-lg font-bold text-gray-900 flex items-center mb-4">
            <span class="section-title-bar"></span> Top Performers
        </h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
                    <th class="pb-2 text-left">Developer</th>
                    <th class="pb-2 text-left">Rata-rata Skor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPerformers as $dev)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 font-medium text-gray-900">{{ $dev->name }}</td>
                    <td class="py-3 font-bold text-green-700">{{ number_format($dev->tasks_avg_calculated_score ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-gray-400 py-6 text-sm">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Reward Decision Center --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-lg font-bold text-gray-900 flex items-center mb-4">
            <span class="section-title-bar"></span> Reward Decision Center
        </h3>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
                    <th class="pb-2 text-left">Developer</th>
                    <th class="pb-2 text-left">Rata-rata Skor</th>
                    <th class="pb-2 text-left">Bonus Diusulkan</th>
                    <th class="pb-2 text-left">Status</th>
                    <th class="pb-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recommendations as $rec)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 font-medium text-gray-900">{{ $rec->developer->name ?? '-' }}</td>
                    <td class="py-3 text-gray-700">{{ number_format($rec->average_score, 2) }}</td>
                    <td class="py-3 text-gray-700">Rp {{ number_format($rec->proposed_bonus, 0, ',', '.') }}</td>
                    <td class="py-3">
                        {{-- MENAMPILKAN BADGE STATUS SECARA DINAMIS BERDASARKAN DATABASE (FR-19) --}}
                        @if($rec->recommendation_status === 'pending')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu</span>
                        @elseif($rec->recommendation_status === 'approved')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                        @endif
                    </td>
                    <td class="py-3">
                        {{-- MEMENUHI NFR-13: TOMBOL HANYA MUNCUL JIKA STATUS MASIH PENDING, JIKA SUDAH DISAKSIKAN AKAN OTOMATIS TERKUNCI --}}
                        @if($rec->recommendation_status === 'pending')
                        <div class="flex gap-2">
                            {{-- PERBAIKAN UTAMA: Mengirimkan parameter ID ($rec->id) secara tegas agar Route Model Binding di Controller memproses baris data yang tepat --}}
                            <form method="POST" action="{{ route('admin.rewards.approve', $rec->id) }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-green-600 hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.rewards.reject', $rec->id) }}">
                                @csrf
                                <input type="hidden" name="rejection_reason" value="Ditolak oleh Product Owner.">
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-red-600 hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-gray-400 font-medium italic">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-400 py-6 text-sm">
                        Belum ada rekomendasi kenaikan gaji.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>

@endsection