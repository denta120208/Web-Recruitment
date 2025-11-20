<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'accepted_terms_at',
        'date_of_birth',
        'role',
        'location_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function applyJobs()
    {
        return $this->hasMany(ApplyJob::class, 'user_id', 'id');
    }

    public function applicant()
    {
        return $this->hasOne(Applicant::class, 'user_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Role helper methods
    public function isApplicant()
    {
        return $this->role === 'applicant';
    }

    public function isAdminLocation()
    {
        return $this->role === 'admin_location';
    }

    public function isAdminPusat()
    {
        return $this->role === 'admin_pusat';
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin_location', 'admin_pusat']);
    }

    public function canAccessLocation($locationId)
    {
        // Admin pusat bisa akses semua lokasi
        if ($this->isAdminPusat()) {
            return true;
        }

        // Admin lokasi hanya bisa akses lokasi mereka sendiri
        if ($this->isAdminLocation()) {
            return $this->location_id == $locationId;
        }

        return false;
    }
}
