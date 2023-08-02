<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardShowController extends Controller
{
    public function __invoke(Request $request, $id): View
    {
        $dashboard = Dashboard::find($id);
        // dd($id, $dashboard);
        return view('dashboard', compact('dashboard'));
    }
}
