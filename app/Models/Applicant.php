<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class Applicant extends Model
{
    protected $table = 'require';
    protected $primaryKey = 'requireid';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    
    const CREATED_AT = 'createdat';
    const UPDATED_AT = 'updatedat';
    
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'gender',
        'dateofbirth',
        'address',
        'city',
        'gmail',
        'linkedin',
        'instagram',
        'phone',
        'cvpath',
        'photopath',
        'user_id',
        'createdat',
        'updatedat',
        'marital_status',
        'is_fresh_graduate',
        'ref1_name',
        'ref1_address_phone',
        'ref1_occupation',
        'ref1_relationship',
        'ref2_name',
        'ref2_address_phone',
        'ref2_occupation',
        'ref2_relationship',
        'ref3_name',
        'ref3_address_phone',
        'ref3_occupation',
        'ref3_relationship',
        'emergency1_name',
        'emergency1_address',
        'emergency1_phone',
        'emergency1_relationship',
        'emergency2_name',
        'emergency2_address',
        'emergency2_phone',
        'emergency2_relationship',
        'q11_willing_outside_jakarta',
        'q14_current_income',
        'q15_expected_income',
        'q16_available_from',
    ];

    protected $casts = [
        'createdat' => 'datetime',
        'updatedat' => 'datetime',
    ];

    /**
     * Mutator: encrypt DateOfBirth before saving
     */
    public function setDateOfBirthAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['dateofbirth'] = null;
            return;
        }

        // accept Carbon/Date or string input; store RFC3339 string encrypted
        $date = $value instanceof \DateTime ? Carbon::parse($value)->toDateString() : (string) $value;
        $this->attributes['dateofbirth'] = Crypt::encryptString($date);
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
        $this->attributes['gmail'] = is_null($value) ? null : Crypt::encryptString((string) $value);
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
        $this->attributes['phone'] = is_null($value) ? null : Crypt::encryptString((string) $value);
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
        return $this->hasMany(RequireWorkExperience::class, 'requireid', 'requireid');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(RequireEducation::class, 'requireid', 'requireid');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(RequireTraining::class, 'requireid', 'requireid');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applyJobs(): HasMany
    {
        return $this->hasMany(ApplyJob::class, 'requireid', 'requireid');
    }
}
