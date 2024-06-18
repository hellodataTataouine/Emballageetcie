<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\SubscribedUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
    
        // Load country names and flags
        $countries = [];
        $countryJson = File::get(storage_path('app/countries.json'));
        
        $countryData = json_decode($countryJson, true);
    
        foreach ($totalTodayVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            // $flagPath = storage_path("app/flags/{$countryCode}.png");
            // dd($flagPath);
            // if (file_exists($flagPath) && isset($countryData[$countryCode])) {
                if (isset($countryData[$countryCode])) {
                $countries[$visit->country]['name'] = $countryData[$countryCode];
                // $countries[$visit->country]['flag'] = asset("storage/flags/{$countryCode}.png");
                $countries[$visit->country]['today'] = $visit->total;
                
            }
        }
    
        foreach ($totalWeekVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            if (isset($countries[$visit->country])) {
                $countries[$visit->country]['week'] = $visit->total;
            }
        }
    
        foreach ($totalYearVisits as $visit) {
            $countryCode = strtoupper($visit->country);
            if (isset($countries[$visit->country])) {
                $countries[$visit->country]['year'] = $visit->total;
            }
        }
        
        return view('backend.pages.visits.index', [
            'countries' => $countries,
        ]);
    }
}