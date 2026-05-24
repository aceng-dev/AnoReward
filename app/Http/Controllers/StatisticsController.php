<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SalaryRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Menampilkan statistik dashboard untuk developer
     */
    public function developerStats()
    {
        $developer = auth()->user();

        // Task statistics
        $tasks = Task::where('developer_id', $developer->id)->get();
        $completedTasks = $tasks->where('status', 'done');
        $activeTasks = $tasks->whereIn('status', ['todo', 'inprogress']);
        $overdueTasks = $tasks->filter(function($task) {
            return $task->deadline && $task->deadline < now() && in_array($task->status, ['todo', 'inprogress']);
        });

        // Points statistics
        $totalPoints = $developer->total_points ?? 0;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Reward statistics
        $rewards = SalaryRecommendation::where('developer_id', $developer->id)->get();
        $approvedRewards = $rewards->where('status', 'approved');
        $pendingRewards = $rewards->where('status', 'pending');

        // Get rank
        $rank = User::where('role', 'developer')
            ->whereRaw('total_points > ?', [$totalPoints ?? 0])
            ->count() + 1;

        $stats = [
            'tasks' => [
                'total' => $tasks->count(),
                'completed' => $completedTasks->count(),
                'in_progress' => $tasks->where('status', 'inprogress')->count(),
                'todo' => $tasks->where('status', 'todo')->count(),
                'overdue' => $overdueTasks->count(),
                'completion_rate' => ($tasks->count() > 0) ? ($completedTasks->count() / $tasks->count()) * 100 : 0,
            ],
            'points' => [
                'total' => $totalPoints,
                'this_month' => $completedTasks->filter(function($task) use ($currentMonth, $currentYear) {
                    return $task->completed_at && 
                           $task->completed_at->month == $currentMonth && 
                           $task->completed_at->year == $currentYear;
                })->sum('points'),
                'average_per_task' => $completedTasks->count() > 0 ? round($completedTasks->sum('points') / $completedTasks->count()) : 0,
            ],
            'rewards' => [
                'approved_total' => $approvedRewards->sum('amount'),
                'pending_total' => $pendingRewards->sum('amount'),
                'approved_count' => $approvedRewards->count(),
                'pending_count' => $pendingRewards->count(),
                'average_reward' => $approvedRewards->count() > 0 ? round($approvedRewards->avg('amount')) : 0,
            ],
            'ranking' => [
                'rank' => $rank,
                'total_developers' => User::where('role', 'developer')->count(),
                'percentile' => round(((User::where('role', 'developer')->count() - $rank + 1) / User::where('role', 'developer')->count()) * 100),
            ],
            'performance' => [
                'average_rating' => round($completedTasks->avg('rating') ?? 0, 2),
                'top_project' => $this->getTopProject($developer),
                'busy_month' => $this->getBusiestMonth($developer),
            ],
        ];

        return view('statistics.developer', ['stats' => $stats]);
    }

    /**
     * Menampilkan statistik dashboard untuk manager
     */
    public function managerStats()
    {
        $manager = auth()->user();

        if ($manager->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        // Get semua developer yang di-manage (bisa di-filter berdasarkan team)
        $developers = User::where('role', 'developer')->get();

        $stats = [
            'team' => [
                'total_developers' => $developers->count(),
                'active_developers' => $developers->filter(function($dev) {
                    return $dev->tasks()->where('status', 'inprogress')->exists();
                })->count(),
            ],
            'tasks' => [
                'total_assigned' => Task::whereIn('developer_id', $developers->pluck('id'))->count(),
                'completed' => Task::whereIn('developer_id', $developers->pluck('id'))
                    ->where('status', 'done')
                    ->count(),
                'in_progress' => Task::whereIn('developer_id', $developers->pluck('id'))
                    ->where('status', 'inprogress')
                    ->count(),
                'overdue' => Task::whereIn('developer_id', $developers->pluck('id'))
                    ->whereDate('due_date', '<', now()->toDateString())
                    ->whereIn('status', ['todo', 'inprogress'])
                    ->count(),
            ],
            'rewards' => [
                'pending_approval' => SalaryRecommendation::whereIn('developer_id', $developers->pluck('id'))
                    ->where('recommendation_status', 'pending')
                    ->count(),
                'total_pending_amount' => SalaryRecommendation::whereIn('developer_id', $developers->pluck('id'))
                    ->where('recommendation_status', 'pending')
                    ->sum('proposed_bonus'),
                'approved_this_month' => SalaryRecommendation::whereIn('developer_id', $developers->pluck('id'))
                    ->where('recommendation_status', 'approved')
                    ->whereMonth('evaluated_at', now()->month)
                    ->count(),
            ],
            'performance' => [
                'average_completion_rate' => $this->calculateAverageCompletionRate($developers),
                'top_performer' => $this->getTopPerformer($developers),
                'at_risk_developers' => $this->getAtRiskDevelopers($developers),
            ],
        ];

        return view('statistics.manager', [
            'stats' => $stats,
            'developers' => $developers,
        ]);
    }

    /**
     * Menampilkan statistik dashboard untuk admin
     */
    public function adminStats()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $allUsers = User::all();
        $developers = User::where('role', 'developer')->get();
        $managers = User::where('role', 'manager')->get();

        $stats = [
            'users' => [
                'total' => $allUsers->count(),
                'developers' => $developers->count(),
                'managers' => $managers->count(),
                'admins' => User::where('role', 'admin')->count(),
            ],
            'tasks' => [
                'total' => Task::count(),
                'completed' => Task::where('status', 'done')->count(),
                'in_progress' => Task::where('status', 'inprogress')->count(),
                'overdue' => Task::whereDate('due_date', '<', now()->toDateString())
                    ->whereIn('status', ['todo', 'inprogress'])
                    ->count(),
            ],
            'rewards' => [
                'total_distributed' => SalaryRecommendation::where('recommendation_status', 'approved')->sum('proposed_bonus'),
                'pending_approval' => SalaryRecommendation::where('recommendation_status', 'pending')->sum('proposed_bonus'),
                'average_reward' => round(SalaryRecommendation::where('recommendation_status', 'approved')->avg('proposed_bonus')),
            ],
            'projects' => [
                'total' => \App\Models\Project::count(),
            ],
        ];

        return view('statistics.admin', ['stats' => $stats]);
    }

    /**
     * Helper: Get top project for a developer
     */
    private function getTopProject($developer)
    {
        $topProject = Task::where('developer_id', $developer->id)
            ->where('status', 'done')
            ->groupBy('project_id')
            ->selectRaw('project_id, COUNT(*) as count, SUM(points) as total_points')
            ->orderBy('total_points', 'desc')
            ->first();

        return $topProject?->project?->name ?? 'N/A';
    }

    /**
     * Helper: Get busiest month for a developer
     */
    private function getBusiestMonth($developer)
    {
        $busiestMonth = Task::where('developer_id', $developer->id)
            ->where('status', 'done')
            ->selectRaw('MONTH(completed_at) as month, YEAR(completed_at) as year, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('count', 'desc')
            ->first();

        if (!$busiestMonth) return 'N/A';

        return Carbon::create($busiestMonth->year, $busiestMonth->month)->format('F Y');
    }

    /**
     * Helper: Calculate average completion rate
     */
    private function calculateAverageCompletionRate($developers)
    {
        $rates = $developers->map(function($dev) {
            $total = $dev->tasks()->count();
            if ($total === 0) return 0;
            return ($dev->tasks()->where('status', 'done')->count() / $total) * 100;
        });

        return round($rates->avg());
    }

    /**
     * Helper: Get top performer
     */
    private function getTopPerformer($developers)
    {
        return $developers->sortByDesc('total_points')->first();
    }

    /**
     * Helper: Get at-risk developers (overdue tasks)
     */
    private function getAtRiskDevelopers($developers)
    {
        return $developers->filter(function($dev) {
            return $dev->tasks()
                ->whereDate('due_date', '<', now()->toDateString())
                ->whereIn('status', ['todo', 'inprogress'])
                ->exists();
        })->values();
    }
}
