<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Menampilkan leaderboard global semua developer
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'points'); // points atau tasks_completed
        $timeframe = $request->get('timeframe', 'all'); // all, month, week

        $query = User::where('role', 'developer');

        // Filter berdasarkan timeframe
        if ($timeframe === 'month') {
            $query->whereMonth('updated_at', now()->month)
                  ->whereYear('updated_at', now()->year);
        } elseif ($timeframe === 'week') {
            $query->whereBetween('updated_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        }

        // Sort berdasarkan kriteria
        if ($sortBy === 'points') {
            $query->orderBy('total_points', 'desc');
        } else {
            $query->orderByRaw('(SELECT COUNT(*) FROM tasks WHERE developer_id = users.id AND status = "done") DESC');
        }

        $developers = $query->get()
            ->map(function($user, $index) {
                $user->rank = $index + 1;
                $user->completed_tasks = $user->tasks()
                    ->where('status', 'done')
                    ->count();
                $user->percentage = ($user->total_points / 5000) * 100; // Assume max 5000 points
                return $user;
            });

        return view('leaderboard.index', [
            'developers' => $developers,
            'totalDevelopers' => $developers->count(),
            'topDeveloper' => $developers->first(),
            'currentUser' => auth()->user(),
            'sortBy' => $sortBy,
            'timeframe' => $timeframe,
        ]);
    }

    /**
     * Menampilkan detail profil developer di leaderboard
     */
    public function show(User $developer)
    {
        if ($developer->role !== 'developer') {
            abort(404);
        }

        // Ambil rank
        $rank = User::where('role', 'developer')
            ->whereRaw('total_points > ?', [$developer->total_points ?? 0])
            ->count() + 1;

        $completedTasks = $developer->tasks()
            ->where('status', 'done')
            ->orderBy('completed_at', 'desc')
            ->get();

        $rewards = $developer->salaryRecommendations()
            ->where('recommendation_status', 'approved')
            ->orderBy('evaluated_at', 'desc')
            ->get();

        $stats = [
            'total_tasks' => $developer->tasks()->count(),
            'completed_tasks' => $completedTasks->count(),
            'in_progress_tasks' => $developer->tasks()->where('status', 'inprogress')->count(),
            'total_points' => $developer->total_points,
            'total_rewards' => $rewards->sum('amount'),
            'average_rating' => $completedTasks->avg('rating') ?? 0,
            'completion_rate' => ($completedTasks->count() / ($developer->tasks()->count() ?: 1)) * 100,
        ];

        return view('leaderboard.show', [
            'developer' => $developer,
            'rank' => $rank,
            'stats' => $stats,
            'completedTasks' => $completedTasks->take(10),
            'rewards' => $rewards->take(5),
        ]);
    }

    /**
     * API endpoint untuk data leaderboard (JSON)
     */
    public function api(Request $request)
    {
        $limit = $request->get('limit', 50);
        $offset = $request->get('offset', 0);

        $developers = User::where('role', 'developer')
            ->orderBy('total_points', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function($user, $index) use ($offset) {
                return [
                    'rank' => $offset + $index + 1,
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_points' => $user->total_points,
                    'completed_tasks' => $user->tasks()->where('status', 'done')->count(),
                    'profile_image' => $user->profile_photo_path,
                ];
            });

        return response()->json([
            'data' => $developers,
            'total' => User::where('role', 'developer')->count(),
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Menampilkan statistik leaderboard
     */
    public function statistics()
    {
        $developers = User::where('role', 'developer')->get();

        $topDevelopers = $developers->sortByDesc('total_points')->values();
        $top10 = $topDevelopers->take(10)->map(function($dev, $index) {
            $dev->rank = $index + 1;
            return $dev;
        });

        $stats = [
            'total_developers' => $developers->count(),
            'total_points_distributed' => $developers->sum('total_points'),
            'average_points_per_developer' => $developers->avg('total_points'),
            'top_10_developers' => $top10,
            'points_distribution' => [
                '0-100' => $developers->filter(fn($dev) => $dev->total_points < 100)->count(),
                '100-500' => $developers->filter(fn($dev) => $dev->total_points >= 100 && $dev->total_points < 500)->count(),
                '500-1000' => $developers->filter(fn($dev) => $dev->total_points >= 500 && $dev->total_points < 1000)->count(),
                '1000+' => $developers->filter(fn($dev) => $dev->total_points >= 1000)->count(),
            ],
        ];

        return view('leaderboard.statistics', ['stats' => $stats]);
    }
}
