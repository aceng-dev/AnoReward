<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\SalaryRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectManagerController extends Controller
{
    /**
     * Halaman utama dashboard Project Manager.
     */
    public function index()
    {
        $developers = User::where('role', 'developer')->get();

        $doneTasks = Task::with(['developer', 'project'])
            ->where('status', 'done')
            ->latest()
            ->get();

        return view('dashboard.manager', compact('developers', 'doneTasks'));
    }

    /**
     * Simpan proyek baru beserta tugas pertamanya.
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'task_name'    => 'required|string|max:255',
            'developer_id' => 'required|exists:users,id',
            'due_date'     => 'required|date',
            'difficulty'   => 'required|in:Low,Medium,High',
        ]);

        $project = Project::create([
            'project_name' => $request->project_name,
            'status'       => 'planning',
            'created_by'   => Auth::id(),
        ]);

        Task::create([
            'project_id'   => $project->id,
            'developer_id' => $request->developer_id,
            'task_name'    => $request->task_name,
            'description'  => $request->description ?? null,
            'difficulty'   => $request->difficulty,
            'status'       => 'todo',
            'due_date'     => $request->due_date,
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'Proyek dan Tugas berhasil dibuat!');
    }

    /**
     * Edit project.
     */
    public function editProject(Project $project)
    {
        $developers = User::where('role', 'developer')->get();
        return view('manager.project.edit', compact('project', 'developers'));
    }

    /**
     * Update project.
     */
    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
        ]);

        $project->update([
            'project_name' => $request->project_name,
            'status'       => $request->status ?? $project->status,
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Hapus project (beserta tasks-nya via cascade).
     */
    public function destroyProject(Project $project)
    {
        $project->delete();

        return redirect()->route('manager.dashboard')
            ->with('success', 'Proyek berhasil dihapus!');
    }

    /**
     * Edit task.
     */
    public function editTask(Task $task)
    {
        if ($task->status === 'approved') {
            return redirect()->route('manager.dashboard')
                ->with('error', 'Task yang sudah di-approve tidak dapat diedit.');
        }

        $developers = User::where('role', 'developer')->get();
        return view('manager.task.edit', compact('task', 'developers'));
    }

    /**
     * Update task (hanya jika belum approved).
     */
    public function updateTask(Request $request, Task $task)
    {
        if ($task->status === 'approved') {
            return redirect()->route('manager.dashboard')
                ->with('error', 'Task yang sudah di-approve tidak dapat diedit.');
        }

        $request->validate([
            'task_name'    => 'required|string|max:255',
            'developer_id' => 'required|exists:users,id',
            'due_date'     => 'required|date',
            'difficulty'   => 'required|in:Low,Medium,High',
            'description'  => 'nullable|string',
        ]);

        $task->update([
            'task_name'    => $request->task_name,
            'developer_id' => $request->developer_id,
            'due_date'     => $request->due_date,
            'difficulty'   => $request->difficulty,
            'description'  => $request->description,
        ]);

        return redirect()->route('manager.dashboard')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Proses penilaian task oleh PM + hitung skor otomatis.
     */
   public function approveTask(Request $request, $id)
{
    $request->validate([
        'pm_rating' => 'required|integer|min:1|max:100',
    ]);

    $task = Task::findOrFail($id);
    $nilaiKualitas = (int) $request->pm_rating;

    // PERBAIKAN BUG: Gunakan tanggal selesai dari developer (completed_at), bukan hari ini (Carbon::today())
    // Jika developer belum submit tapi PM memaksa approve, gunakan fallback waktu sekarang.
    $completionDate = $task->completed_at ? Carbon::parse($task->completed_at)->startOfDay() : Carbon::today();
    $dueDate = Carbon::parse($task->due_date)->startOfDay();

    if ($completionDate->lte($dueDate)) {
        $poinWaktu = 100;
    } else {
        $hariTerlambat = $completionDate->diffInDays($dueDate);
        $poinWaktu = max(0, 100 - ($hariTerlambat * 10)); // Sesuai PRD: -10 per hari terlambat
    }

    // PERBAIKAN FORMULA (FR-15): Hapus difficulty multiplier agar murni sesuai formula PRD
    $taskScore = ($nilaiKualitas * 0.6) + ($poinWaktu * 0.4);

    $task->update([
        'pm_rating'        => $nilaiKualitas,
        'calculated_score' => (int) round($taskScore),
        'status'           => 'approved',
        'completed_at'     => $task->completed_at ?? now(), 
    ]);

    // Update Poin Real-time
    $developer = $task->developer;
    if ($developer) {
        $developer->decrement('total_points', 100); 
        $developer->increment('total_points', (int) round($taskScore)); 
    }

    // Cek kelayakan kenaikan gaji
    $this->checkSalaryEligibility($task->developer_id);

    return redirect()->route('manager.dashboard')
        ->with('success', 'Tugas Berhasil Dinilai dan Dikunci sesuai Formula PRD!');
}

private function checkSalaryEligibility($developerId)
{
    $bulanIni  = Carbon::now()->startOfMonth();
    $bulanLalu = Carbon::now()->subMonth()->startOfMonth();
    $akhirBulanLalu = Carbon::now()->subMonth()->endOfMonth();

    $avgBulanIni = Task::where('developer_id', $developerId)
        ->where('status', 'approved')
        ->whereYear('completed_at', $bulanIni->year)
        ->whereMonth('completed_at', $bulanIni->month)
        ->avg('calculated_score');

    $avgBulanLalu = Task::where('developer_id', $developerId)
        ->where('status', 'approved')
        ->where('completed_at', '>=', $bulanLalu)
        ->where('completed_at', '<=', $akhirBulanLalu)
        ->avg('calculated_score');

    // PERBAIKAN LOGIKA (FR-17): Wajib memiliki data di kedua bulan dan keduanya harus > 85 (Tanpa Fallback)
    if (is_null($avgBulanIni) || is_null($avgBulanLalu)) {
        return; // Data belum lengkap 2 bulan berturut-turut, batalkan evaluasi
    }

    $layak = ($avgBulanIni > 85) && ($avgBulanLalu > 85);

    if (!$layak) {
        return;
    }

    // Hindari duplikasi rekomendasi di bulan yang sama
    $sudahAda = SalaryRecommendation::where('developer_id', $developerId)
        ->where('recommendation_status', 'pending')
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->exists();

    if ($sudahAda) {
        return;
    }

    $rataRataGabungan = collect([$avgBulanIni, $avgBulanLalu])->avg();
    $developer     = User::find($developerId);
    $gajiSekarang  = $developer->current_salary ?? 0;
    $proposedBonus = $gajiSekarang * 0.05; // Otomatisasi saran persentase kenaikan (FR-18)

    SalaryRecommendation::create([
        'developer_id'          => $developerId,
        'average_score'         => round($rataRataGabungan, 2),
        'recommendation_status' => 'pending',
        'manager_comments'      => 'Sistem Otomatis: Rata-rata score ' . round($rataRataGabungan, 2) . ' selama 2 bulan berturut-turut memenuhi threshold organisasi.',
        'proposed_bonus'        => $proposedBonus,
        'evaluated_at'          => now(),
    ]);
}
}