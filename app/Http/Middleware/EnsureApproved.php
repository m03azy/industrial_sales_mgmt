<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->status === 'suspended') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended.');
        }

        if (auth()->user()->status === 'pending') {
            return redirect()->route('dashboard')->with('warning', 'Your account is pending approval.');
        }

        return $next($request);
    }
}
