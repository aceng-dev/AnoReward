<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Tampilkan detail task
     */
    public function show(Task $task)
    {
        // Cek apakah user adalah developer yang mengerjakan task ini
        if ($task->developer_id !== auth()->id() && auth()->user()->role !== 'manager' && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Mulai mengerjakan task (ubah status ke in_progress)
     */
    public function start(Task $task)
    {
        // Cek apakah user adalah developer yang mengerjakan task ini
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Update status
        $task->update(['status' => 'in_progress']);

        return redirect()->back()->with('success', 'Task dimulai! 🚀');
    }

    /**
     * Selesaikan task (ubah status ke completed)
     */
    public function complete(Task $task)
    {
        // Cek apakah user adalah developer yang mengerjakan task ini
        if ($task->developer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Update status dan completed_at
        $task->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Task diselesaikan! ✅');
    }

    /**
     * Update task status
     */
    public function updateStatus(Request $task, $id)
    {
        $task = Task::findOrFail($id);

        // Cek apakah user adalah developer yang mengerjakan task ini
        if ($task->developer_id !== auth()->id() && auth()->user()->role !== 'manager' && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        if ($validated['status'] === 'completed') {
            $task->update([
                'status' => $validated['status'],
                'completed_at' => Carbon::now(),
            ]);
        } else {
            $task->update($validated);
        }

        return back()->with('success', 'Status task diperbarui!');
    }
}
