<?php

namespace App\Http\Controllers;

use App\Models\SalaryRecommendation;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryDecisionController extends Controller
{
    /**
     * Menyetujui rekomendasi kenaikan gaji (Product Owner)
     * Sesuai dengan FR-19 dan FR-20 pada dokumen PRD
     */
    public function approve(SalaryRecommendation $recommendation)
    {
        // 1. Validasi awal: Pastikan statusnya masih 'pending' untuk mencegah fraud / double click
        if ($recommendation->recommendation_status !== 'pending') {
            return redirect()->back()->with('error', 'Rekomendasi ini sudah pernah diproses sebelumnya.');
        }

        // 2. Update status rekomendasi menjadi 'approved' di database sesuai FR-19
        $recommendation->update([
            'recommendation_status' => 'approved',
            'evaluated_at'          => now(),
        ]);

        // 3. Ambil data developer terkait
        $developer = User::find($recommendation->developer_id);

        if ($developer) {
            // Antisipasi Bug 1: Jika current_salary masih 0 atau null di database, 
            // berikan nilai standar minimum standar (misal: Rp 5.000.000) agar persentase bonus tidak bernilai Rp 0
            if (!$developer->current_salary || $developer->current_salary == 0) {
                $developer->current_salary = 5000000;
                $developer->save();
            }

            // Tambahkan nominal bonus yang diusulkan ke dalam kolom current_salary milik developer (FR-20)
            $developer->increment('current_salary', $recommendation->proposed_bonus);

            // [Opsional] Sesuai FR-20: Anda bisa memicu log notifikasi selamat ke dashboard developer di sini
        }

        return redirect()->back()->with('success', 'Kenaikan gaji berhasil disetujui dan nilai gaji di database telah diperbarui.');
    }

    /**
     * Menolak rekomendasi kenaikan gaji (Product Owner)
     * Sesuai dengan FR-19 pada dokumen PRD
     */
    public function reject(SalaryRecommendation $recommendation)
    {
        // Validasi awal: Pastikan statusnya masih 'pending'
        if ($recommendation->recommendation_status !== 'pending') {
            return redirect()->back()->with('error', 'Rekomendasi ini sudah pernah diproses sebelumnya.');
        }

        // Update status rekomendasi menjadi 'rejected' di database sesuai FR-19
        $recommendation->update([
            'recommendation_status' => 'rejected',
            'evaluated_at'          => now(),
        ]);

        return redirect()->back()->with('success', 'Rekomendasi kenaikan gaji berhasil ditolak.');
    }
}