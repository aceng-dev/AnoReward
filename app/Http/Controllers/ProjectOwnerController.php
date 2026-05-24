<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\SalaryRecommendation;

class ProjectOwnerController extends Controller
{
    public function index()
    {
        $totalDeveloper = User::where('role', 'developer')->count();

        $approvedTasks = Task::where('status', 'approved')->count();

        $averageProductivity = Task::avg('task_score');

        $topPerformers = User::where('role', 'developer')
            ->withAvg('tasks', 'task_score')
            ->orderByDesc('tasks_avg_task_score')
            ->take(5)
            ->get();

        $recommendations = SalaryRecommendation::with('user')
            ->latest()
            ->get();

        return view('dashboard.admin', compact(
            'totalDeveloper',
            'approvedTasks',
            'averageProductivity',
            'topPerformers',
            'recommendations'
        ));
    }
}