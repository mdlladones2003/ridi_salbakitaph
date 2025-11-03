<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'user_id',
        'post_id',
        'content'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
