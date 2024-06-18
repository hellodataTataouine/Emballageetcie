<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\SubscribedUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitsController extends Controller{
    public function index()
    {
        $today = Carbon::today();
        $todayVisits = DB::table('visits')
            ->whereDate('created_at', $today)
            ->get();
        $startOfWeek = Carbon::now()->startOfWeek();

        $weekVisits = DB::table('visits')
            ->where('created_at', '>=', $startOfWeek)
            ->get();
            $startOfYear = Carbon::now()->startOfYear();

        $yearVisits = DB::table('visits')
            ->where('created_at', '>=', $startOfYear)
            ->get();
        // You can also order the results if needed
        // $visitCounts = $visitCounts->sortByDesc('count');
        // $totalTodayVisits = $todayVisits->sum('count');
        // $totalWeekVisits = $weekVisits->sum('count');
        // $totalYearVisits = $yearVisits->sum('count');
        return view('backend.pages.visits.index', compact('totalTodayVisits','totalWeekVisits','totalYearVisits'));
    }
}