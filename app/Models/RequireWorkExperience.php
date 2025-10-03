<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequireWorkExperience extends Model
{
    protected $table = 'RequireWorkExperience';
    protected $primaryKey = 'WorkID';
    public $timestamps = false;
    
    protected $fillable = [
        'RequireID',
        'CompanyName',
        'JobLevel',
        'StartDate',
        'EndDate',
        'Salary'
    ];

    protected $casts = [
        'StartDate' => 'date',
        'EndDate' => 'date',
        'Salary' => 'decimal:2'
    ];

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'RequireID', 'RequireID');
    }
}
