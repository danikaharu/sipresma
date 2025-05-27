<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Level;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $acamedicCountByLevel = Level::select('levels.name', DB::raw('COUNT(*) as total'))
            ->join('awards', 'awards.level_id', '=', 'levels.id')
            ->join('activities', 'activities.award_id', '=', 'awards.id')
            ->where('award_type', 1)
            ->groupBy('levels.name')
            ->get();

        $nonAcamedicCountByLevel = Level::select('levels.name', DB::raw('COUNT(*) as total'))
            ->join('awards', 'awards.level_id', '=', 'levels.id')
            ->join('activities', 'activities.award_id', '=', 'awards.id')
            ->where('award_type', 2)
            ->groupBy('levels.name')
            ->get();

        return view('admin.dashboard.index', compact('acamedicCountByLevel', 'nonAcamedicCountByLevel'));
    }

    public function chartData()
    {
        $data = Activity::selectRaw('YEAR(start_date) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return response()->json($data);
    }
}
