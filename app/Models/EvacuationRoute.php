<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class EvacuationRoute extends Model
{
    use HasFactory;

    protected $primaryKey = 'route_id';

    protected $fillable = [
        'barangay_id',
        'route_name',
        'start_point',
        'end_point',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function barangay() {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }
}
