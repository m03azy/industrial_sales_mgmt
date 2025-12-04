<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFactory
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'factory') {
            abort(403, 'Access denied. Factory role required.');
        }

        if (auth()->user()->status !== 'approved') {
            abort(403, 'Your factory account is pending approval.');
        }

        return $next($request);
    }
}
