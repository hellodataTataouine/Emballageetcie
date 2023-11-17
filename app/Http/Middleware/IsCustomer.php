<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'customer') {
                return $next($request);
            } else {
                flash(localize('Veuillez vous connecter en tant que client pour continuer.'))->error();
                return redirect()->route('home');
            }
        } else {
            session(['link' => url()->current()]);
            flash(localize('Veuillez vous connecter pour continuer.'))->error();
            return redirect()->route('login');
        }
    }
}
