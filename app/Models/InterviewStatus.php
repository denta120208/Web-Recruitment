<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewStatus extends Model
{
    protected $table = 'interview_status';
    protected $primaryKey = 'interview_status_id';
    public $timestamps = false;
    
    protected $fillable = [
        'interview_status_name',
    ];
}
