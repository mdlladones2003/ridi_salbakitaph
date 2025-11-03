<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class DisasterUpdate extends Model
{
    use HasFactory;

    protected $primaryKey = 'disaster_id';

    protected $fillable = [
        'type',
        'content',
        'affected_area'
    ];

    // Relationships
    public function alerts() {
        return $this->hasMany(Alert::class, 'disaster_id', 'disaster_id');
    }
}
