<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class JobVacancy extends Model
{
    protected $table = 'job_vacancy';
    protected $primaryKey = 'job_vacancy_id';
    public $timestamps = true;
    
    protected $fillable = [
        'job_request_hris_id',
        'job_title_hris_id',
        'job_vacancy_level_name',
        'job_vacancy_name',
        'job_vacancy_job_desc',
        'job_vacancy_job_spec',
        'job_vacancy_status_id',
        'job_vacancy_hris_location_id',
        'job_vacancy_start_date',
        'job_vacancy_end_date',
        'job_vacancy_man_power',
    ];

    protected $casts = [
        'job_vacancy_start_date' => 'date',
        'job_vacancy_end_date' => 'date',
        'job_vacancy_man_power' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function applyJobs(): HasMany
    {
        return $this->hasMany(ApplyJob::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'job_vacancy_hris_location_id', 'hris_location_id');
    }

    // Scope for active jobs (status 1,2,3,4 and within date range)
    public function scopeActive($query)
    {
        return $query->whereIn('job_vacancy_status_id', [1, 2, 3, 4])
                    ->where('job_vacancy_start_date', '<=', Carbon::now())
                    ->where('job_vacancy_end_date', '>=', Carbon::now());
    }

    // Check if job is still active
    public function isActive()
    {
        return in_array($this->job_vacancy_status_id, [1, 2, 3, 4]) &&
               $this->job_vacancy_start_date <= Carbon::now() &&
               $this->job_vacancy_end_date >= Carbon::now();
    }

    // Get count of hired candidates (status 5)
    public function getHiredCountAttribute()
    {
        return $this->applyJobs()
            ->where('apply_jobs_status', 5) // Status Hired
            ->count();
    }

    // Get available positions (man_power - hired count)
    public function getAvailablePositionsAttribute()
    {
        $hiredCount = $this->hired_count;
        $manPower = $this->job_vacancy_man_power ?? 0;
        $available = max(0, $manPower - $hiredCount); // Ensure non-negative
        return $available;
    }

    // Get location name for display
    public function getLocationAttribute()
    {
        if ($this->job_vacancy_hris_location_id) {
            $location = Location::where('hris_location_id', $this->job_vacancy_hris_location_id)->first();
            return $location ? $location->name : null;
        }
        return null;
    }

}
