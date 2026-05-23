<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        // Mengarah ke resources/views/dashboard/admin.blade.php
        return view('dashboard.admin');
    }

    public function manager()
    {
        // Mengarah ke resources/views/dashboard/manager.blade.php
        return view('dashboard.manager');
    }

    public function developer()
    {
        // Mengarah ke resources/views/dashboard/developer.blade.php
        return view('dashboard.developer');
    }
}