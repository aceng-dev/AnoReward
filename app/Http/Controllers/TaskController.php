<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar task untuk developer (Kanban Board)
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $developer */
        $developer = auth()->user();
        $status    = $request->get('status');

        $allTasks = Task::where('developer_id', $developer->id)
            ->orderBy('due_date', 'asc')
            ->get();

        if (in_array($status, ['todo', 'inprogress', 'done'])) {
            $tasks = $allTasks->where('status', $status)->values();
        } else {
            $tasks = $allTasks;
        }

        $tasks = $tasks->map(function ($task) {
            $task->days_left = now()->diffInDays($task->due_date, false);
            return $task;
        });

        return view('tasks.index', [
            'tasks'         => $tasks,
            'taskCounts'    => [
                'all'        => $allTasks->count(),
                'todo'       => $allTasks->where('status', 'todo')->count(),
                'inprogress' => $allTasks->where('status', 'inprogress')->count(),
                'done'       => $allTasks->where('status', 'done')->count(),
            ],
            'activeCount'    => $allTasks->whereIn('status', ['todo', 'inprogress'])->count(),
            'completedCount' => $allTasks->where('status', 'done')->count(),
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Menampilkan detail task
     */
    public function show(Task $task)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role !== 'admin' && $user->role !== 'manager' && $task->developer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('tasks.show', [
            'task'    => $task,
            'project' => $task->project,
        ]);
    }

    /**
     * Memulai mengerjakan task (Todo → In Progress)
     */
    public function start(Task $task)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($task->developer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($task->status !== 'todo') {
            return redirect()->back()->with('error', 'Task hanya bisa dimulai dari status Todo.');
        }

        $task->update([
            'status' => 'inprogress',
        ]);

        return redirect()->back()->with('success', 'Task berhasil dimulai!');
    }

    /**
     * Menyelesaikan task (In Progress → Done)
     */
    public function complete(Task $task, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($task->developer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($task->status !== 'inprogress') {
            return redirect()->back()->with('error', 'Task harus dalam status "Sedang Dikerjakan" untuk diselesaikan.');
        }

        $request->validate([
            'repo_link' => 'required|url|max:500',
        ]);

        $task->update([
            'status'       => 'done',
            'completed_at' => Carbon::now(),
            'repo_link'    => $request->repo_link,
        ]);

        // Tambah poin sementara ke developer
        $this->addPointsToDeveloper($task);

        return redirect()->back()->with('success', 'Task berhasil diselesaikan! Menunggu penilaian dari Project Manager.');
    }

    /**
     * Update status task via form (untuk Kanban drag-drop sederhana)
     */
    public function updateStatus(Task $task, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'status' => 'required|in:todo,inprogress,done',
        ]);

        if ($task->developer_id !== $user->id && $user->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        // Task yang sudah approved tidak bisa diubah
        if ($task->status === 'approved') {
            return redirect()->back()->with('error', 'Task yang sudah diapprove tidak dapat diubah.');
        }

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        // Validasi transisi status
        $allowedTransitions = [
            'todo'       => ['inprogress'],
            'inprogress' => ['done'],
            'done'       => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            return redirect()->back()->with('error', "Transisi status dari {$oldStatus} ke {$newStatus} tidak diperbolehkan.");
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'done') {
            $updateData['completed_at'] = Carbon::now();
            $this->addPointsToDeveloper($task);
        }

        $task->update($updateData);

        return redirect()->back()->with('success', 'Status task berhasil diubah.');
    }

    /**
     * Helper: Tambah poin sementara ke developer saat task Done
     */
    private function addPointsToDeveloper(Task $task)
    {
        $developer = $task->developer;
        if ($developer) {
            $poin = 100; // poin dasar saat done, akan diupdate saat approved
            $developer->increment('total_points', $poin);
            Log::info("Poin ditambahkan ke {$developer->name}: {$poin} poin untuk task {$task->task_name}");
        }
    }
}