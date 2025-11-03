<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Barangay extends Model
{
    use HasFactory;

    protected $primaryKey = 'barangay_id';

    protected $fillable = [
        'name',
        'municipality',
        'province',
        'latitude',
        'longitude',
        'risk_level'
    ];

    // Relationships
    public function reports() {
        return $this->hasMany(Report::class, 'barangay_id', 'barangay_id');
    }

    public function evacuationRoutes() {
        return $this->hasMany(EvacuationRoute::class, 'barangay_id', 'barangay_id');
    }

    public function evacuationCenters() {
        return $this->hasMany(EvacuationCenter::class, 'barangay_id', 'barangay_id');
    }
}
