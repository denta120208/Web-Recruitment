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
        'apply_jobs_interview_pic',
        'apply_jobs_interview_user_email',
        'apply_jobs_interview_location',
        'apply_jobs_interview_date',
        'apply_jobs_interview_time',
        'apply_jobs_interview_result',
        'apply_jobs_interview_ai_result',
        'apply_jobs_interview_status',
        'apply_jobs_interview_email_sent',
        'apply_jobs_interview_file',
        'apply_jobs_psikotest_iq_num',
        'apply_jobs_psikotest_date',
        'apply_jobs_psikotest_time',
        'apply_jobs_psikotest_location',
        'apply_jobs_psikotest_status',
        'apply_jobs_psikotest_file',
        'apply_jobs_psikotest_email_sent',
        'apply_jobs_mcu_file',
        'apply_jobs_mcu_date',
        'apply_jobs_mcu_time',
        'apply_jobs_mcu_location',
        'apply_jobs_mcu_email_sent',
        'apply_jobs_offering_letter_file',
        'apply_jobs_offering_letter_date',
        'apply_jobs_offering_email_sent',
        'apply_jobs_join_date',
        'apply_jobs_join_email_sent',
        'is_generated_employee',
        'requireid',
        'require_id',
        'apply_date',
    ];

    protected $casts = [
        'apply_jobs_psikotest_iq_num' => 'integer',
        'is_generated_employee' => 'boolean',
        'apply_jobs_interview_email_sent' => 'boolean',
        'apply_jobs_psikotest_email_sent' => 'boolean',
        'apply_jobs_mcu_email_sent' => 'boolean',
        'apply_jobs_offering_email_sent' => 'boolean',
        'apply_jobs_join_email_sent' => 'boolean',
        'apply_date' => 'date',
        'apply_jobs_interview_date' => 'date',
        'apply_jobs_interview_time' => 'datetime:H:i:s',
        'apply_jobs_psikotest_date' => 'date',
        'apply_jobs_psikotest_time' => 'datetime:H:i:s',
        'apply_jobs_mcu_date' => 'date',
        'apply_jobs_mcu_time' => 'datetime:H:i:s',
        'apply_jobs_offering_letter_date' => 'date',
        'apply_jobs_join_date' => 'date',
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
