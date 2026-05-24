<?php

namespace App\Http\Controllers;

use App\Models\SalaryRecommendation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RewardController extends Controller
{
    /**
     * Menampilkan daftar reward/bonus untuk developer
     */
    public function index()
    {
        $developer = auth()->user();

        $rewards = SalaryRecommendation::where('developer_id', $developer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApproved = $rewards->where('status', 'approved')->sum('amount');
        $pendingApproval = $rewards->where('status', 'pending')->sum('amount');

        return view('rewards.index', [
            'rewards' => $rewards,
            'totalApproved' => $totalApproved,
            'pendingApproval' => $pendingApproval,
            'totalRewards' => $totalApproved + $pendingApproval,
        ]);
    }

    /**
     * Menampilkan detail reward
     */
    public function show(SalaryRecommendation $reward)
    {
        // Cek apakah user adalah pemilik reward atau admin/manager
        if ($reward->developer_id !== auth()->id() && auth()->user()->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        return view('rewards.show', [
            'reward' => $reward,
            'developer' => $reward->developer,
            'manager' => $reward->manager,
            'task' => $reward->task,
        ]);
    }

    /**
     * Create reward (untuk manager/admin)
     */
    public function create()
    {
        // Hanya manager dan admin yang bisa membuat reward
        if (auth()->user()->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        return view('rewards.create');
    }

    /**
     * Store reward (untuk manager/admin)
     */
    public function store(Request $request)
    {
        if (auth()->user()->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'developer_id' => 'required|exists:users,id',
            'task_id' => 'nullable|exists:tasks,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
            'period_month' => 'required|date',
        ]);

        $reward = SalaryRecommendation::create([
            'developer_id' => $validated['developer_id'],
            'average_score' => 0,
            'proposed_bonus' => $validated['amount'],
            'manager_comments' => $validated['reason'],
            'evaluated_at' => $validated['period_month'],
            'recommendation_status' => 'pending',
        ]);

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.rewards.show' : 'manager.rewards.show';

        return redirect()->route($redirectRoute, $reward)
            ->with('success', 'Reward berhasil dibuat dan menunggu approval');
    }

    /**
     * Approve reward (hanya admin)
     */
    public function approve(SalaryRecommendation $reward)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa approve reward');
        }

        if ($reward->status !== 'pending') {
            return redirect()->back()->with('error', 'Reward ini sudah di-process');
        }

        $reward->update([
            'recommendation_status' => 'approved',
            'evaluated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Reward berhasil diapprove');
    }

    /**
     * Reject reward (hanya admin)
     */
    public function reject(SalaryRecommendation $reward, Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa reject reward');
        }

        if ($reward->status !== 'pending') {
            return redirect()->back()->with('error', 'Reward ini sudah di-process');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $reward->update([
            'recommendation_status' => 'rejected',
            'manager_comments' => $validated['rejection_reason'],
            'evaluated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Reward berhasil ditolak');
    }

    /**
     * Menampilkan statistik reward
     */
    public function statistics()
    {
        $user = auth()->user();

        if ($user->role === 'developer') {
            // Stats untuk developer
            $rewards = SalaryRecommendation::where('developer_id', $user->id)->get();
        } else {
            // Stats untuk manager/admin (all developers)
            $rewards = SalaryRecommendation::all();
        }

        $stats = [
            'total_distributed' => $rewards->where('status', 'approved')->sum('amount'),
            'total_pending' => $rewards->where('status', 'pending')->sum('amount'),
            'total_rejected' => $rewards->where('status', 'rejected')->count(),
            'average_reward' => $rewards->where('status', 'approved')->avg('amount'),
            'monthly_breakdown' => $rewards->groupBy('period_month')->map(fn($group) => [
                'total' => $group->where('status', 'approved')->sum('amount'),
                'count' => $group->count(),
            ]),
        ];

        return view('rewards.statistics', ['stats' => $stats]);
    }
}
