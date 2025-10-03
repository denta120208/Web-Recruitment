<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequireTraining extends Model
{
    protected $table = 'RequireTraining';
    protected $primaryKey = 'TrainingID';
    public $timestamps = false;
    
    protected $fillable = [
        'RequireID',
        'TrainingName',
        'CertificateNo',
        'StartTrainingDate',
        'EndTrainingDate'
    ];

    protected $casts = [
        'StartTrainingDate' => 'date',
        'EndTrainingDate' => 'date'
    ];

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'RequireID', 'RequireID');
    }
}
