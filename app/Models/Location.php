<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'hris_location_id',
        'name',
        'country_code',
        'province',
        'city',
        'address',
        'zip_code',
        'phone',
    ];

    protected $casts = [
        'hris_location_id' => 'integer',
    ];

    /**
     * Get location by HRIS location ID
     */
    public static function getByHrisId($hrisLocationId)
    {
        return static::where('hris_location_id', $hrisLocationId)->first();
    }

    /**
     * Get location name by HRIS location ID
     */
    public static function getNameByHrisId($hrisLocationId)
    {
        $location = static::getByHrisId($hrisLocationId);
        return $location ? $location->name : null;
    }
}
