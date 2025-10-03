<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequireEducation extends Model
{
    protected $table = 'RequireEducation';
    protected $primaryKey = 'EduID';
    public $timestamps = false;
    
    protected $fillable = [
        'RequireID',
        'InstitutionName',
        'Major',
        'StartDate',
        'EndDate'
    ];

    protected $casts = [
        'StartDate' => 'date',
        'EndDate' => 'date'
    ];

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'RequireID', 'RequireID');
    }
}
