<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRetailer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'retailer') {
            abort(403, 'Access denied. Retailer role required.');
        }

        if (auth()->user()->status !== 'approved') {
            abort(403, 'Your retailer account is pending approval.');
        }

        return $next($request);
    }
}
