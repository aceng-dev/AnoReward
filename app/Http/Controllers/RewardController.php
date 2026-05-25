<?php

namespace App\Http\Controllers;

use App\Models\SalaryRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RewardController extends Controller
{
    /**
     * Daftar reward:
     * - developer: hanya miliknya sendiri
     * - admin/manager: semua rekomendasi (Reward Decision Center per FR-18)
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === 'developer') {
            $rewards = SalaryRecommendation::where('developer_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalApproved   = $rewards->where('recommendation_status', 'approved')->sum('proposed_bonus');
            $pendingApproval = $rewards->where('recommendation_status', 'pending')->sum('proposed_bonus');

            return view('rewards.index', [
                'rewards'         => $rewards,
                'totalApproved'   => $totalApproved,
                'pendingApproval' => $pendingApproval,
                'totalRewards'    => $totalApproved + $pendingApproval,
            ]);
        }

        // admin / manager: tampilkan semua + developer info
        $rewards = SalaryRecommendation::with('developer')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApproved   = $rewards->where('recommendation_status', 'approved')->sum('proposed_bonus');
        $pendingApproval = $rewards->where('recommendation_status', 'pending')->sum('proposed_bonus');

        return view('rewards.index', [
            'rewards'         => $rewards,
            'totalApproved'   => $totalApproved,
            'pendingApproval' => $pendingApproval,
            'totalRewards'    => $totalApproved + $pendingApproval,
        ]);
    }

    /**
     * Detail reward.
     */
    public function show(SalaryRecommendation $reward)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($reward->developer_id !== $user->id && $user->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        return view('rewards.show', [
            'reward'    => $reward,
            'developer' => $reward->developer,
            'manager'   => $reward->manager,
        ]);
    }

    /**
     * Form buat reward manual (manager/admin).
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        $developers = User::where('role', 'developer')->get();

        return view('rewards.create', compact('developers'));
    }

    /**
     * Simpan reward manual (manager/admin).
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === 'developer') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'developer_id' => 'required|exists:users,id',
            'amount'       => 'required|numeric|min:0',
            'reason'       => 'required|string|max:500',
            'period_month' => 'required|date',
        ]);

        $reward = SalaryRecommendation::create([
            'developer_id'          => $validated['developer_id'],
            'average_score'         => 0,
            'proposed_bonus'        => $validated['amount'],
            'manager_comments'      => $validated['reason'],
            'evaluated_at'          => $validated['period_month'],
            'recommendation_status' => 'pending',
        ]);

        $redirectRoute = $user->role === 'admin'
            ? 'admin.rewards.show'
            : 'manager.rewards.show';

        return redirect()->route($redirectRoute, $reward)
            ->with('success', 'Reward berhasil dibuat dan menunggu approval.');
    }

    /**
     * Approve reward (hanya admin).
     */
    public function approve(SalaryRecommendation $reward)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa approve reward.');
        }

        if ($reward->recommendation_status !== 'pending') {
            return redirect()->back()->with('error', 'Reward ini sudah diproses.');
        }

        $reward->update([
            'recommendation_status' => 'approved',
            'evaluated_at'          => Carbon::now(),
        ]);

        // Tambahkan bonus ke gaji developer
        $developer = User::find($reward->developer_id);
        if ($developer && $reward->proposed_bonus) {
            $developer->increment('current_salary', $reward->proposed_bonus);
        }

        return redirect()->back()->with('success', 'Reward berhasil diapprove.');
    }

    /**
     * Reject reward (hanya admin).
     */
    public function reject(SalaryRecommendation $reward, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa reject reward.');
        }

        if ($reward->recommendation_status !== 'pending') {
            return redirect()->back()->with('error', 'Reward ini sudah diproses.');
        }

        $reward->update([
            'recommendation_status' => 'rejected',
            'manager_comments'      => $request->input('rejection_reason', 'Ditolak oleh admin.'),
            'evaluated_at'          => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Reward berhasil ditolak.');
    }

    /**
     * Statistik reward.
     */
    public function statistics()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $rewards = $user->role === 'developer'
            ? SalaryRecommendation::where('developer_id', $user->id)->get()
            : SalaryRecommendation::all();

        $stats = [
            'total_distributed' => $rewards->where('recommendation_status', 'approved')->sum('proposed_bonus'),
            'total_pending'     => $rewards->where('recommendation_status', 'pending')->sum('proposed_bonus'),
            'total_rejected'    => $rewards->where('recommendation_status', 'rejected')->count(),
            'average_reward'    => $rewards->where('recommendation_status', 'approved')->avg('proposed_bonus') ?? 0,
            'monthly_breakdown' => $rewards->groupBy(function ($r) {
                return optional($r->evaluated_at)->format('Y-m') ?? $r->created_at->format('Y-m');
            })->map(fn ($group) => [
                'total' => $group->where('recommendation_status', 'approved')->sum('proposed_bonus'),
                'count' => $group->count(),
            ]),
        ];

        return view('rewards.statistics', ['stats' => $stats]);
    }
}