<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequireEducation extends Model
{
    protected $table = 'requireeducation';
    protected $primaryKey = 'eduid';
    public $timestamps = false;
    
    protected $fillable = [
        'requireid',
        'institutionname',
        'major',
        'startdate',
        'enddate'
    ];

    protected $casts = [
        'StartDate' => 'date',
        'EndDate' => 'date'
    ];

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'requireid', 'requireid');
    }
}
