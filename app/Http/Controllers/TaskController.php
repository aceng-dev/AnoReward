<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Menampilkan detail task tertentu
     */
    public function show(Task $task)
    {
        // Cek apakah user adalah developer yang ditugaskan atau manager/admin
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager' && $task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('tasks.show', [
            'task' => $task,
            'project' => $task->project,
            'history' => $task->statusHistory()->latest()->get(),
        ]);
    }

    /**
     * Memulai mengerjakan task
     */
    public function start(Task $task)
    {
        // Validasi: hanya developer yang ditugaskan yang bisa memulai
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah task sudah dimulai
        if ($task->status === 'inprogress' || $task->status === 'done') {
            return redirect()->back()->with('error', 'Task sudah dimulai atau selesai');
        }

        // Update task status
        $task->update([
            'status' => 'inprogress',
            'started_at' => Carbon::now(),
        ]);

        // Catat history status
        $task->statusHistory()->create([
            'old_status' => 'todo',
            'new_status' => 'inprogress',
            'changed_by' => auth()->id(),
            'changed_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Task berhasil dimulai');
    }

    /**
     * Menyelesaikan task
     */
    public function complete(Task $task)
    {
        // Validasi: hanya developer yang ditugaskan yang bisa menyelesaikan
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah task sedang dikerjakan
        if ($task->status !== 'inprogress') {
            return redirect()->back()->with('error', 'Task harus dalam status "Sedang Dikerjakan"');
        }

        // Update task status
        $task->update([
            'status' => 'done',
            'completed_at' => Carbon::now(),
        ]);

        // Catat history status
        $task->statusHistory()->create([
            'old_status' => 'inprogress',
            'new_status' => 'done',
            'changed_by' => auth()->id(),
            'changed_at' => Carbon::now(),
        ]);

        // Tambah poin developer
        $this->addPointsToDeveloper($task);

        return redirect()->back()->with('success', 'Task berhasil diselesaikan! Poin ditambahkan.');
    }

    /**
     * Mengubah status task
     */
    public function updateStatus(Task $task, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,inprogress,done,approved',
        ]);

        // Cek hak akses
        if ($task->developer_id !== auth()->id() && auth()->user()->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        // Logika validasi status
        $allowedTransitions = [
            'todo' => ['inprogress'],
            'inprogress' => ['done'],
            'done' => [],
            'approved' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            return redirect()->back()->with('error', "Transisi status dari {$oldStatus} ke {$newStatus} tidak diperbolehkan");
        }

        // Update status
        $task->update(['status' => $newStatus]);

        // Catat history
        $task->statusHistory()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'changed_at' => Carbon::now(),
        ]);

        // Jika selesai, tambah poin
        if ($newStatus === 'done' && $oldStatus !== 'done') {
            $this->addPointsToDeveloper($task);
        }

        return redirect()->back()->with('success', 'Status task berhasil diubah');
    }

    /**
     * Helper: Menambah poin ke developer
     */
    private function addPointsToDeveloper(Task $task)
    {
        $developer = $task->developer;
        $developer->increment('total_points', $task->points ?? 100);

        // Catat ke history poin
        if ($developer) {
            // Simplified - you might want to create a PointHistory model
            Log::info("Points added to {$developer->name}: {$task->points} points for task {$task->title}");
        }
    }

    /**
     * Menampilkan daftar task untuk developer
     */
    public function index(Request $request)
    {
        $developer = auth()->user();
        $status = $request->get('status');

        $baseQuery = Task::where('developer_id', $developer->id)->orderBy('due_date', 'asc');
        $allTasks = $baseQuery->get();

        if (in_array($status, ['todo', 'inprogress', 'done'])) {
            $tasks = $baseQuery->where('status', $status)->get();
        } else {
            $tasks = $allTasks;
        }

        $tasks = $tasks->map(function($task) {
            $task->days_left = now()->diffInDays($task->due_date, false);
            return $task;
        });

        return view('tasks.index', [
            'tasks' => $tasks,
            'taskCounts' => [
                'all' => $allTasks->count(),
                'todo' => $allTasks->where('status', 'todo')->count(),
                'inprogress' => $allTasks->where('status', 'inprogress')->count(),
                'done' => $allTasks->where('status', 'done')->count(),
            ],
            'activeCount' => $allTasks->whereIn('status', ['todo', 'inprogress'])->count(),
            'completedCount' => $allTasks->where('status', 'done')->count(),
            'selectedStatus' => $status,
        ]);
    }
}
