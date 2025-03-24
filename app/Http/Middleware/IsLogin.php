<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class IsLogin
{
   
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return Redirect()->route('login');
        }
        return $next($request);
    }
}
