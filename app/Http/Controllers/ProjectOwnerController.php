<?php

namespace App\Http\Controllers;

use App\Models\SalaryRecommendation;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryDecisionController extends Controller
{
    public function approve(SalaryRecommendation $recommendation)
    {
        if ($recommendation->recommendation_status !== 'pending') {
            return back()->with('error', 'Rekomendasi ini sudah diproses.');
        }

        $recommendation->update([
            'recommendation_status' => 'approved',
            'evaluated_at'          => now(),
        ]);

        // Tambahkan bonus ke gaji developer
        $developer = User::find($recommendation->developer_id);
        if ($developer && $recommendation->proposed_bonus) {
            $developer->increment('current_salary', $recommendation->proposed_bonus);
        }

        return back()->with('success', 'Kenaikan gaji berhasil disetujui.');
    }

    public function reject(SalaryRecommendation $recommendation, Request $request)
    {
        if ($recommendation->recommendation_status !== 'pending') {
            return back()->with('error', 'Rekomendasi ini sudah diproses.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $recommendation->update([
            'recommendation_status' => 'rejected',
            'manager_comments'      => $request->input('rejection_reason', 'Ditolak oleh Product Owner.'),
            'evaluated_at'          => now(),
        ]);

        return back()->with('success', 'Rekomendasi kenaikan gaji ditolak.');
    }
}