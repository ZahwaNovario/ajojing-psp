<?php

namespace App\Http\Controllers\Admin;

use Spatie\Activitylog\Models\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::where('log_name', 'auth')
            ->latest()
            ->get();

        return view('admin.activity-log.login-log.index', compact('activities'));
    }

    public function itemLog()
    {
        $activities = Activity::where('log_name', 'Product')
            ->latest()
            ->get();

        return view('admin.activity-log.items-log.index', compact('activities'));
    }
}
