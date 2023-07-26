<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()){
            return redirect()->route('login');
        }
        // role 1 = admin
        if (auth()->user()->role ==1 ){
            return redirect()->route('admin/dashboard');
        }
        // role 2 = user
        if (auth()->user()->role ==2 ){
            return redirect()->route('admin/dashboard');
//            return $next($request);
        }
        // role 3 = supervisor
        if (auth()->user()->role ==3 ){
            return redirect()->route('admin/dashboard');
//            return redirect()->route('supervisor/dashboard');
        }
    }
}
