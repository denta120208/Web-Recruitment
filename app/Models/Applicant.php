<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class Applicant extends Model
{
    protected $table = 'Require';
    protected $primaryKey = 'RequireID';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    
        protected $fillable = [
            'FirstName',
            'MiddleName',
            'LastName',
            'Gender',
            'DateOfBirth',
            'CVPath',
            'PhotoPath',
            'Address',
            'City',
            'Gmail',
            'LinkedIn',
            'Instagram',
            'Phone',
            'status',
            'admin_notes',
            'status_updated_at',
            'reviewed_by',
            'user_id'
        ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    /**
     * Mutator: encrypt DateOfBirth before saving
     */
    public function setDateOfBirthAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['DateOfBirth'] = null;
            return;
        }

        // accept Carbon/Date or string input; store RFC3339 string encrypted
        $date = $value instanceof \DateTime ? Carbon::parse($value)->toDateString() : (string) $value;
        $this->attributes['DateOfBirth'] = Crypt::encryptString($date);
    }

    /**
     * Accessor: decrypt DateOfBirth when accessing
     */
    public function getDateOfBirthAttribute($value)
    {
        if (is_null($value)) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            // return as Carbon date for convenience
            return Carbon::parse($decrypted);
        } catch (\Exception $e) {
            // if decryption fails, return raw value to avoid breaking
            return $value;
        }
    }

    /**
     * Mutator/accessor for Gmail
     */
    public function setGmailAttribute($value)
    {
        $this->attributes['Gmail'] = is_null($value) ? null : Crypt::encryptString((string) $value);
    }

    public function getGmailAttribute($value)
    {
        if (is_null($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Mutator/accessor for Phone
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['Phone'] = is_null($value) ? null : Crypt::encryptString((string) $value);
    }

    public function getPhoneAttribute($value)
    {
        if (is_null($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    // Relationships
    public function workExperiences(): HasMany
    {
        return $this->hasMany(RequireWorkExperience::class, 'RequireID', 'RequireID');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(RequireEducation::class, 'RequireID', 'RequireID');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(RequireTraining::class, 'RequireID', 'RequireID');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper methods for status
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'under_review' => 'Sedang Direview',
            'interview_scheduled' => 'Interview Dijadwalkan',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'hired' => 'Sudah Dipekerjakan',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'under_review' => 'info',
            'interview_scheduled' => 'primary',
            'accepted' => 'success',
            'rejected' => 'danger',
            'hired' => 'success',
            default => 'gray'
        };
    }
}
