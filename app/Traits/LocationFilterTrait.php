<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait LocationFilterTrait
{
    /**
     * Apply location filter based on current user's role
     */
    public function applyLocationFilter(Builder $query, $locationColumn = 'job_vacancy_hris_location_id'): Builder
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return $query;
        }

        // Admin pusat bisa lihat semua data
        if ($user->isAdminPusat()) {
            return $query;
        }

        // Admin lokasi hanya bisa lihat data dari lokasi mereka
        if ($user->isAdminLocation() && $user->location_id) {
            // Ambil hris_location_id dari location user
            $location = $user->location;
            if ($location && $location->hris_location_id) {
                return $query->where($locationColumn, $location->hris_location_id);
            }
        }

        // Jika tidak ada kondisi yang terpenuhi, return query kosong
        return $query->whereRaw('1 = 0');
    }

    /**
     * Get accessible location IDs for current user
     */
    public function getAccessibleLocationIds(): array
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return [];
        }

        // Admin pusat bisa akses semua lokasi
        if ($user->isAdminPusat()) {
            return \App\Models\Location::pluck('hris_location_id')->toArray();
        }

        // Admin lokasi hanya bisa akses lokasi mereka
        if ($user->isAdminLocation() && $user->location_id) {
            $location = $user->location;
            return $location && $location->hris_location_id ? [$location->hris_location_id] : [];
        }

        return [];
    }

    /**
     * Check if current user can access specific location
     */
    public function canAccessLocationId($hrisLocationId): bool
    {
        $accessibleIds = $this->getAccessibleLocationIds();
        return empty($accessibleIds) || in_array($hrisLocationId, $accessibleIds);
    }
}
