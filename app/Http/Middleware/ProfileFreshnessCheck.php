<?php

namespace App\Http\Middleware;

use App\Models\Applicant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;

class ProfileFreshnessCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $applicant = Applicant::where('user_id', $user->id)->first();
        if (!$applicant) {
            return $next($request);
        }

        // Fields are named CreatedAt/UpdatedAt on the table; cast present in model
        $updatedAt = $applicant->UpdatedAt; // model already casts to datetime
        try {
            $updatedCarbon = $updatedAt instanceof Carbon ? $updatedAt : Carbon::parse((string) $updatedAt);
        } catch (\Throwable $e) {
            return $next($request);
        }

        // Mark as stale when older than 3 months OR suspicious future timestamps (> now + 1 day)
        $isStale = $updatedCarbon->lt(Carbon::now()->subMonthsNoOverflow(3)) || $updatedCarbon->gt(Carbon::now()->addDay());
        if ($isStale) {
            // If profile is stale (>3 months), require update
            // Only redirect if user is not already on edit/update routes
            if (!$request->routeIs('applicant.edit') && !$request->routeIs('applicant.update')) {
                return redirect()
                    ->route('applicant.edit', $applicant->RequireID)
                    ->with('warning', 'Profil Anda berusia lebih dari 3 bulan. Mohon perbarui data Anda.');
            }
        }

        return $next($request);
    }
}
