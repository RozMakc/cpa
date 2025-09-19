<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActivation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_active == 0) {
            $allowedRoutes = ['activation', 'logout'];
            
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('activation');
            }
        }else if(auth()->check() && auth()->user()->is_active == 1 && $request->route()->getName() == 'activation') {
			
			return redirect()->route('dashboard');
		}

        return $next($request);
    }
}