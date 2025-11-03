<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class EvacuationCenter extends Model
{
    use HasFactory;

    protected $primaryKey = 'center_id';

    protected $fillable = [
        'barangay_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'capacity',
        'current_occupancy',
        'facilities',
        'contact_number',
        'is_active'
    ];

    protected $casts = [
        'facilities'    => 'array',
        'is_active'     => 'boolean'
    ];

    // Relationships
    public function barangay() { return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id'); }
}
