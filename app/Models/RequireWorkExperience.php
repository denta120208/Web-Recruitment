<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequireWorkExperience extends Model
{
    protected $table = 'requireworkexperience';
    protected $primaryKey = 'workid';
    public $timestamps = false;
    
    protected $fillable = [
        'requireid',
        'companyname',
        'joblevel',
        'startdate',
        'enddate',
        'iscurrent',
        'salary',
        'eexp_comments'
    ];

    protected $casts = [
        'startdate' => 'date',
        'enddate' => 'date',
        'salary' => 'decimal:2'
    ];

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'requireid', 'requireid');
    }
}
