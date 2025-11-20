<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LocationAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user tidak login atau bukan admin, lanjutkan
        if (!$user || !$user->isAdmin()) {
            return $next($request);
        }

        // Set global scope untuk filtering data berdasarkan lokasi
        if ($user->isAdminLocation()) {
            // Admin lokasi hanya bisa lihat data dari lokasi mereka
            app()->instance('user_location_filter', $user->location_id);
        } elseif ($user->isAdminPusat()) {
            // Admin pusat bisa lihat semua data
            app()->instance('user_location_filter', null);
        }

        return $next($request);
    }
}
