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

        // Get current time and 3 months ago threshold
        $now = Carbon::now()->startOfDay();
        $threeMonthsAgo = $now->copy()->subMonths(3)->startOfDay();

        // Debug: Log the dates for checking
        \Illuminate\Support\Facades\Log::info('Profile Check Dates', [
            'UpdatedAt' => $updatedCarbon->copy()->startOfDay()->toDateString(),
            'Current' => $now->toDateString(),
            'ThreeMonthsAgo' => $threeMonthsAgo->toDateString(),
            'Is Profile 3 Months or Older' => $updatedCarbon->copy()->startOfDay()->lte($threeMonthsAgo)
        ]);

        // Check if profile is exactly 3 months or older (compare only date)
        $isStale = $updatedCarbon->copy()->startOfDay()->lte($threeMonthsAgo);
        
        if ($isStale) {
            // If profile is 3 months old or older, require update
            // Only redirect if user is not already on edit/update routes
            if (!$request->routeIs('applicant.edit') && !$request->routeIs('applicant.update')) {
                return redirect()
                    ->route('applicant.edit', $applicant->RequireID)
                    ->with('warning', "Profil Anda berusia lebih dari 3 bulan. Mohon perbarui data Anda.");
            }
        }

        return $next($request);
    }
}
