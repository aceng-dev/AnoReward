<?php

namespace App\Http\Controllers;

use App\Models\SalaryRecommendation;

class SalaryDecisionController extends Controller
{
    public function approve(SalaryRecommendation $recommendation)
    {
        $recommendation->update([
            'approval_status' => 'approved'
        ]);

        return back()
            ->with('success', 'Salary increase approved');
    }

    public function reject(SalaryRecommendation $recommendation)
    {
        $recommendation->update([
            'approval_status' => 'rejected'
        ]);

        return back()
            ->with('success', 'Salary increase rejected');
    }
}