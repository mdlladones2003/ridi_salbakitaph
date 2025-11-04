<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'barangay_id',
        'type',
        'severity',
        'status',
        'content',
        'media',
        'latitude',
        'longitude',
        'report_verified_count',
        'affected_count',
        'reported_at',
        'resolved_at'
    ];

    protected $casts = [
        'media'         => 'array',
        'reported_at'   => 'datetime',
        'resolved_at'   => 'datetime'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function barangay() {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function verifications() {
        return $this->hasMany(Verification::class, 'report_id', 'report_id');
    }
}
