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
}
