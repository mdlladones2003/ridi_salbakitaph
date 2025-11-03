<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory};

class Reaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'reaction_id';

    protected $fillable = [
        'user_id',
        'post_id',
        'emoji_type'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
