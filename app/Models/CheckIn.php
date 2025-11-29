<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class CheckIn extends Model
{
    use HasFactory;

    protected $primaryKey = 'check_in_id';

    protected $fillable = [
        'user_id',
        'status'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
