<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $excludedRoutes = [
        'admin.*', // Example: Exclude all routes under 'admin' prefix
        'api.*',   // Example: Exclude all API routes
    ];
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();
        // Check if route name is null (route not named)
        if (!$routeName) {
            return $next($request); // Skip logging if route name is null
        }
        foreach ($this->excludedRoutes as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return $next($request); // Skip counting visits for excluded routes
            }
        }
        // Update or insert the visit count for this route
        DB::table('visits')->updateOrInsert(
            ['route_name' => $routeName],
            ['count' => DB::raw('count + 1')]
        );
        return $next($request);
    }
}
