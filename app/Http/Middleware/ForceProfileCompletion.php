<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Applicant;

class ForceProfileCompletion
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
            // Cek apakah user sudah memiliki profil
            $hasProfile = Applicant::where('user_id', $user->id)->exists();
            
            // Jika belum punya profil dan mencoba akses halaman selain create, redirect ke create
            if (!$hasProfile && !$request->routeIs('applicant.create') && !$request->routeIs('applicant.store')) {
                return redirect()->route('applicant.create')->with('warning', 'Silakan lengkapi data diri Anda terlebih dahulu.');
            }
            
            // Jika sudah punya profil dan mencoba akses create, redirect ke index (landing page)
            if ($hasProfile && $request->routeIs('applicant.create')) {
                return redirect()->route('applicant.index');
            }
        }
        
        return $next($request);
    }
}