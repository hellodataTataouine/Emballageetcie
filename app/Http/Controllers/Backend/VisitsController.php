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
    
        // Fetch country names and flags from Country Flags API
        $countries = [];
        foreach ($totalTodayVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            $response = Http::get("https://www.countryflags.io/{$countryCode}/flat/64.png");
            if ($response->successful()) {
                $countries[$visit->country]['name'] = $response->json()['name'];
                $countries[$visit->country]['flag'] = $response->body();
                $countries[$visit->country]['today'] = $visit->total;
            }
        }
    
        foreach ($totalWeekVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            $response = Http::get("https://www.countryflags.io/{$countryCode}/flat/64.png");
            if ($response->successful()) {
                $countries[$visit->country]['week'] = $visit->total;
            }
        }
    
        foreach ($totalYearVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            $response = Http::get("https://www.countryflags.io/{$countryCode}/flat/64.png");
            if ($response->successful()) {
                $countries[$visit->country]['year'] = $visit->total;
            }
        }
        return view('backend.pages.visits.index', [
            'countries' => $countries,
        ]);
    }
}