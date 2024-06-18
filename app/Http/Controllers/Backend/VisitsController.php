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

        // Total visits today grouped by country
        $totalTodayVisits = DB::table('visits')
            ->select('country', DB::raw('count(*) as total'))
            ->whereDate('created_at', $today)
            ->groupBy('country')
            ->get();
    
        $startOfWeek = Carbon::now()->startOfWeek();
    
        // Total visits this week grouped by country
        $totalWeekVisits = DB::table('visits')
            ->select('country', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $startOfWeek)
            ->groupBy('country')
            ->get();
    
        $startOfYear = Carbon::now()->startOfYear();
    
        // Total visits this year grouped by country
        $totalYearVisits = DB::table('visits')
            ->select('country', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $startOfYear)
            ->groupBy('country')
            ->get();
            
    
        $countries = collect()
            ->merge($totalTodayVisits->pluck('country'))
            ->merge($totalWeekVisits->pluck('country'))
            ->merge($totalYearVisits->pluck('country'))
            ->unique()
            ->values();
        return view('visits', [
            'countries' => $countries,
            'totalTodayVisits' => $totalTodayVisits,
            'totalWeekVisits' => $totalWeekVisits,
            'totalYearVisits' => $totalYearVisits,
        ]);
    }
}