<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SalaryRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function admin()
    {
        // Ambil statistik admin
        $stats = [
            'total_users' => User::count(),
            'total_developers' => User::where('role', 'developer')->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'done')->count(),
            'overdue_tasks' => Task::whereDate('due_date', '<', now()->toDateString())
                ->whereIn('status', ['todo', 'inprogress'])
                ->count(),
            'total_rewards_distributed' => SalaryRecommendation::where('recommendation_status', 'approved')->sum('proposed_bonus'),
            'pending_rewards' => SalaryRecommendation::where('recommendation_status', 'pending')->sum('proposed_bonus'),
        ];

        $recentActivities = [
            'recent_completed_tasks' => Task::where('status', 'done')
                ->latest('completed_at')
                ->take(5)
                ->get(),
            'pending_reward_approvals' => SalaryRecommendation::where('recommendation_status', 'pending')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Manager Dashboard
     */
    public function manager()
    {
        $manager = auth()->user();

        // Ambil statistik manager
        $stats = [
            'team_size' => User::where('role', 'developer')->count(), // Simplified - ideally filter by team
            'active_tasks' => Task::where('status', 'inprogress')->count(),
            'completed_tasks_this_month' => Task::where('status', 'done')
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'pending_rewards' => SalaryRecommendation::where('recommendation_status', 'pending')->count(),
            'total_pending_amount' => SalaryRecommendation::where('recommendation_status', 'pending')->sum('proposed_bonus'),
        ];

        $teamData = [
            'developers' => User::where('role', 'developer')->get()->take(10),
            'recent_tasks' => Task::latest('updated_at')->take(10)->get(),
            'pending_approvals' => SalaryRecommendation::where('recommendation_status', 'pending')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.manager', [
            'stats' => $stats,
            'teamData' => $teamData,
        ]);
    }

    /**
     * Developer Dashboard
     */
    public function developer()
    {
        $developer = auth()->user();

        // Ambil tasks developer
        $tasks = Task::where('developer_id', $developer->id)
            ->get()
            ->sortBy(function($task) {
                return $task->deadline;
            });

        $completedTasks = $tasks->where('status', 'done');
        $activeTasks = $tasks->whereIn('status', ['todo', 'inprogress']);

        // Ambil statistik
        $stats = [
            'active_tasks' => $activeTasks->count(),
            'completed_tasks' => $completedTasks->count(),
            'total_points' => $developer->total_points ?? 0,
            'rating_average' => round($completedTasks->avg('rating') ?? 0, 2),
        ];

        // Ambil rank di leaderboard
        $rank = User::where('role', 'developer')
            ->whereRaw('total_points > ?', [$developer->total_points ?? 0])
            ->count() + 1;

        // Ambil rewards
        $rewards = SalaryRecommendation::where('developer_id', $developer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Ambil leaderboard top 5
        $leaderboard = User::where('role', 'developer')
            ->orderBy('total_points', 'desc')
            ->take(5)
            ->get()
            ->map(function($user, $index) {
                $user->rank = $index + 1;
                return $user;
            });

        $totalDevelopers = User::where('role', 'developer')->count();

        return view('dashboard.developer', [
            'stats' => $stats,
            'tasks' => $tasks,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'rewards' => $rewards,
            'currentRank' => $rank,
            'leaderboard' => $leaderboard,
            'developer' => $developer,
            'totalDevelopers' => $totalDevelopers,
        ]);
    }
}