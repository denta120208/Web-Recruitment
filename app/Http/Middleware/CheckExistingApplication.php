<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Applicant;

class CheckExistingApplication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user) {
            // Cek apakah user sudah memiliki lamaran
            $existingApplication = Applicant::where('user_id', $user->id)->first();
            
            if ($existingApplication) {
                // Jika sudah ada profil dan mencoba akses create, redirect ke landing page
                if ($request->routeIs('applicant.create')) {
                    return redirect()->route('applicant.index');
                }
            } else {
                // Jika belum ada, redirect ke create jika mencoba akses edit
                if ($request->routeIs('applicant.edit')) {
                    return redirect()->route('applicant.create');
                }
            }
        }
        
        return $next($request);
    }
}
