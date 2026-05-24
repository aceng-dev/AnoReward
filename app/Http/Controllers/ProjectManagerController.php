<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectManagerController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard Project Manager.
     * Mengambil daftar developer dan tugas yang berstatus 'done'.
     */
    public function index()
    {
        // Ambil semua user dengan role developer untuk dropdown assignee
        $developers = User::where('role', 'developer')->get();

        // Ambil semua tugas yang sudah selesai (status 'done') beserta relasinya
        $doneTasks = Task::with(['developer', 'project'])
            ->where('status', 'done')
            ->latest()
            ->get();

        return view('dashboard.manager', compact('developers', 'doneTasks'));
    }

    /**
     * Menyimpan proyek baru beserta satu tugas pertamanya ke database.
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'project_name'  => 'required|string|max:255',
            'task_name'     => 'required|string|max:255',
            'developer_id'  => 'required|exists:users,id',
            'due_date'      => 'required|date',
            'difficulty'    => 'required|in:Low,Medium,High',
        ]);

        // Buat proyek baru, created_by diisi dengan ID manager yang sedang login
        $project = Project::create([
            'project_name' => $request->project_name,
            'status'       => 'planning',
            'created_by'   => Auth::id(),
        ]);

        // Buat tugas pertama yang terhubung ke proyek di atas
        Task::create([
            'project_id'   => $project->id,
            'developer_id' => $request->developer_id,
            'task_name'    => $request->task_name,
            'description'  => 'Tingkat Kesulitan: ' . $request->difficulty,
            'status'       => 'todo',
            'due_date'     => $request->due_date,
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'Proyek dan Tugas berhasil dibuat!');
    }

    /**
     * Memproses penilaian tugas dari PM dengan perhitungan skor otomatis.
     */
    public function approveTask(Request $request, $id)
    {
        $request->validate([
            'pm_rating' => 'required|integer|min:1|max:100',
        ]);

        // 1. Ambil data task dari database
        $task = Task::findOrFail($id);

        // 2. Tangkap nilai kualitas dari PM (skala 1-100)
        $nilaiKualitas = (int) $request->pm_rating;

        // 3. Hitung Poin Ketepatan Waktu
        $today    = Carbon::today();
        $dueDate  = Carbon::parse($task->due_date);

        if ($today->lte($dueDate)) {
            // Tepat waktu atau sebelum deadline
            $poinWaktu = 100;
        } else {
            // Terlambat: kurangi 10 poin per hari keterlambatan
            $hariTerlambat = $today->diffInDays($dueDate);
            $poinWaktu     = max(0, 100 - ($hariTerlambat * 10));
        }

        // 4. Hitung Skor Akhir dengan pembobotan
        // Task Score = (Nilai Kualitas PM × 0.6) + (Poin Waktu × 0.4)
        $taskScore = ($nilaiKualitas * 0.6) + ($poinWaktu * 0.4);

        // 5. Simpan hasil perhitungan dan kunci tugas
        $task->update([
            'pm_rating'        => $nilaiKualitas,
            'calculated_score' => (int) round($taskScore),
            'status'           => 'approved',    // Dikunci, tidak bisa diubah developer
            'completed_at'     => now(),
        ]);

        // 6. Redirect dengan pesan sukses
        return redirect()->route('manager.dashboard')
            ->with('success', 'Tugas Berhasil Dinilai dan Dikunci!');
    }
}