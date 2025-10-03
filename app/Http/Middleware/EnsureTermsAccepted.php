<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTermsAccepted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && !$user->accepted_terms_at) {
            // allow access to terms route and logout
            if ($request->routeIs('terms.show') || $request->routeIs('terms.accept') || $request->routeIs('logout')) {
                return $next($request);
            }
            return redirect()->route('terms.show');
        }

        return $next($request);
    }
}
