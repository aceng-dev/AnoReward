@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Statistik Leaderboard</h1>
            <a href="{{ route('leaderboard.index') }}" class="text-blue-600 hover:text-blue-900">Kembali</a>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Developer</p>
                <p class="text-4xl font-bold text-gray-900">{{ $stats['total_developers'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Poin Terdistribusi</p>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($stats['total_points_distributed']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Rata-rata Poin/Dev</p>
                <p class="text-3xl font-bold text-blue-600">{{ round($stats['average_points_per_developer']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Developer Tertinggi</p>
                <p class="text-xl font-bold text-purple-600">{{ $stats['top_10_developers']->first()?->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">{{ $stats['top_10_developers']->first()?->total_points }} poin</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top 10 Developers -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Top 10 Developer</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($stats['top_10_developers'] as $dev)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900 w-8 text-center">
                                        @if($dev->rank === 1) 🥇 @elseif($dev->rank === 2) 🥈 @elseif($dev->rank === 3) 🥉 @else #{{ $dev->rank }} @endif
                                    </span>
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-900">{{ $dev->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $dev->email }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900">{{ $dev->total_points }} poin</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Points Distribution -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Distribusi Poin</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $total = $stats['points_distribution']['0-100'] + 
                                     $stats['points_distribution']['100-500'] + 
                                     $stats['points_distribution']['500-1000'] + 
                                     $stats['points_distribution']['1000+'];
                        @endphp

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-900">0-100 poin</span>
                                <span class="text-sm font-bold text-gray-900">{{ $stats['points_distribution']['0-100'] }} ({{ round(($stats['points_distribution']['0-100']/$total)*100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($stats['points_distribution']['0-100']/$total)*100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-900">100-500 poin</span>
                                <span class="text-sm font-bold text-gray-900">{{ $stats['points_distribution']['100-500'] }} ({{ round(($stats['points_distribution']['100-500']/$total)*100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($stats['points_distribution']['100-500']/$total)*100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-900">500-1000 poin</span>
                                <span class="text-sm font-bold text-gray-900">{{ $stats['points_distribution']['500-1000'] }} ({{ round(($stats['points_distribution']['500-1000']/$total)*100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($stats['points_distribution']['500-1000']/$total)*100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-900">1000+ poin</span>
                                <span class="text-sm font-bold text-gray-900">{{ $stats['points_distribution']['1000+'] }} ({{ round(($stats['points_distribution']['1000+']/$total)*100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($stats['points_distribution']['1000+']/$total)*100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
