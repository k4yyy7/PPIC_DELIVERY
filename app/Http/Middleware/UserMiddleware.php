<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if(!Auth::check()){
            return redirect('/login');
        }
        
        if(Auth::user()->role != 'user'){
            if(Auth::user()->role == 'admin'){
                return redirect('/admin/dashboard-admin');
            } else if(Auth::user()->role == 'driver'){
                return redirect('/driver/dashboard-driver');
            }
        }
        
        return $next($request);
    }
}
