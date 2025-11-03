<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Badge extends Model
{
    use HasFactory;

    protected $primaryKey = 'badge_id';

    protected $fillable = [
        'user_id',
        'badge_type',
        'earned_at'
    ];

    protected $casts = [
        'earned_at' => 'datetime'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
