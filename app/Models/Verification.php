<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Verification extends Model
{
    use HasFactory;

    protected $primaryKey = 'verification_id';

    protected $fillable = [
        'report_id',
        'verifier_id',
        'status',
        'notes',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    // Relationships
    public function report() {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function verifier() {
        return $this->belongsTo(User::class, 'verifier_id', 'user_id');
    }
}
