<?php

namespace App\Http\Controllers\Admin;

use Spatie\Activitylog\Models\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // [MODIFIKASI] Kita filter agar hanya mengambil log dengan nama 'auth'
        $activities = Activity::where('log_name', 'auth')
            ->latest() // Urutkan dari yang terbaru
            ->paginate(20); // Gunakan paginasi

        // [MODIFIKASI] Arahkan ke path view baru Anda
        // Tanda titik (.) di Laravel setara dengan tanda garis miring (/) di dalam folder views
        return view('admin.activity-log.login-log.index', compact('activities'));
    }
}
