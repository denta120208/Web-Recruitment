<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplyJob extends Model
{
    protected $table = 'apply_jobs';
    protected $primaryKey = 'apply_jobs_id';
    public $timestamps = true;
    
    protected $fillable = [
        'job_vacancy_id',
        'user_id',
        'apply_jobs_status',
        'apply_jobs_interview_by',
        'apply_jobs_interview_result',
        'apply_jobs_interview_ai_result',
        'apply_jobs_interview_status',
        'apply_jobs_psikotest_iq_num',
        'apply_jobs_psikotest_status',
        'apply_jobs_psikotest_file',
        'apply_jobs_mcu_file',
        'apply_jobs_offering_letter_file',
        'is_generated_employee',
        'requireid',
        'require_id',
    ];

    protected $casts = [
        'apply_jobs_psikotest_iq_num' => 'integer',
        'is_generated_employee' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'requireid', 'requireid');
    }

    public function interviewStatus(): BelongsTo
    {
        return $this->belongsTo(InterviewStatus::class, 'apply_jobs_interview_status', 'interview_status_id');
    }
}
