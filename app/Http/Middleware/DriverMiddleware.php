<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DriverMiddleware
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
        
        if(Auth::user()->role != 'driver'){
            if(Auth::user()->role == 'user'){
                return redirect('/user/dashboard-user');
            } else if(Auth::user()->role == 'admin'){
                return redirect('/admin/dashboard-admin');
            }
        }
        
        return $next($request);
    }
}
