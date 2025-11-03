<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class HelpOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'offer_id';

    protected $fillable = [
        'user_id',
        'offer_type',
        'description',
        'latitude',
        'longitude',
        'is_available',
        'capacity',
        'valid_until'
    ];

    protected $casts = [
        'is_available'  => 'boolean',
        'valid_until'   => 'datetime'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
