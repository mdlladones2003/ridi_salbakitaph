<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Alert extends Model
{
    use HasFactory;

    protected $primaryKey = 'alert_id';

    protected $fillable = [
        'disaster_id',
        'message',
        'severity',
        'is_active',
        'sent_at',
        'expires_at'
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'sent_at'       => 'datetime',
        'expires_at'    => 'datetime'
    ];

    // Relationships
    public function disasterUpdate() {
        return $this->belongsTo(DisasterUpdate::class, 'disaster_id', 'disaster_id');
    }
}
