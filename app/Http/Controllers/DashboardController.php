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
        $totalDeveloper = User::where('role', 'developer')->count();

        $approvedTasks = Task::where('status', 'approved')->count();

        $averageProductivity = Task::where('status', 'approved')
            ->avg('calculated_score') ?? 0;

        $topPerformers = User::where('role', 'developer')
            ->withAvg('tasks', 'calculated_score')
            ->orderByDesc('tasks_avg_calculated_score')
            ->take(5)
            ->get();

        $recommendations = SalaryRecommendation::with('developer')
            ->latest()
            ->get();

        return view('dashboard.admin', [
            'totalDeveloper'      => $totalDeveloper,
            'approvedTasks'       => $approvedTasks,
            'averageProductivity' => $averageProductivity,
            'topPerformers'       => $topPerformers,
            'recommendations'     => $recommendations,
        ]);
    }

    /**
     * Manager Dashboard
     */
    public function manager()
    {
        $manager = auth()->user();

        $stats = [
            'team_size'                  => User::where('role', 'developer')->count(),
            'active_tasks'               => Task::where('status', 'inprogress')->count(),
            'completed_tasks_this_month' => Task::where('status', 'done')
                                               ->whereMonth('completed_at', now()->month)
                                               ->count(),
            'pending_rewards'            => SalaryRecommendation::where('recommendation_status', 'pending')->count(),
            'total_pending_amount'       => SalaryRecommendation::where('recommendation_status', 'pending')->sum('proposed_bonus'),
        ];

        $teamData = [
            'developers'        => User::where('role', 'developer')->get()->take(10),
            'recent_tasks'      => Task::latest('updated_at')->take(10)->get(),
            'pending_approvals' => SalaryRecommendation::where('recommendation_status', 'pending')
                                       ->latest()
                                       ->take(5)
                                       ->get(),
        ];

        return view('dashboard.manager', [
            'stats'    => $stats,
            'teamData' => $teamData,
        ]);
    }

    /**
     * Developer Dashboard
     */
    public function developer()
    {
        $developer = auth()->user();

        $tasks = Task::where('developer_id', $developer->id)
            ->get()
            ->sortBy(function ($task) {
                return $task->deadline;
            });

        $completedTasks = $tasks->where('status', 'done');
        $activeTasks    = $tasks->whereIn('status', ['todo', 'inprogress']);

        $stats = [
            'active_tasks'    => $activeTasks->count(),
            'completed_tasks' => $completedTasks->count(),
            'total_points'    => $developer->total_points ?? 0,
            'rating_average'  => round($completedTasks->avg('rating') ?? 0, 2),
        ];

        $rank = User::where('role', 'developer')
            ->whereRaw('total_points > ?', [$developer->total_points ?? 0])
            ->count() + 1;

        $rewards = SalaryRecommendation::where('developer_id', $developer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $leaderboard = User::where('role', 'developer')
            ->orderBy('total_points', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;
                return $user;
            });

        $totalDevelopers = User::where('role', 'developer')->count();

        return view('dashboard.developer', [
            'stats'           => $stats,
            'tasks'           => $tasks,
            'activeTasks'     => $activeTasks,
            'completedTasks'  => $completedTasks,
            'rewards'         => $rewards,
            'currentRank'     => $rank,
            'leaderboard'     => $leaderboard,
            'developer'       => $developer,
            'totalDevelopers' => $totalDevelopers,
        ]);
    }
}