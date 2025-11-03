<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'reputation_score',
        'role',
        'is_verified',
        'is_active',
        'last_active_at'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_verified'       => 'boolean',
            'is_active'         => 'boolean',
            'last_active_at'    => 'datetime'
        ];
    }

    // Relationships
    public function badges()
    {
        return $this->hasMany(Badge::class, 'user_id', 'user_id');
    }

    public function helpOffers()
    {
        return $this->hasMany(HelpOffer::class, 'user_id', 'user_id');
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'user_id', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'user_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class, 'verifier_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'user_id', 'user_id');
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOfficial()
    {
        return $this->role === 'official' || $this->role === 'admin';
    }

    public function isVolunteer()
    {
        return $this->role === 'volunteer';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
