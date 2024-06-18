<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\SubscribedUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class VisitsController extends Controller{
    public function index()
    {
        $visitCounts = DB::table('visits')->pluck('count', 'route_name');
        
        // You can also order the results if needed
        // $visitCounts = $visitCounts->sortByDesc('count');

        return view('backend.pages.visits.index', compact('visitCounts'));
    }
}