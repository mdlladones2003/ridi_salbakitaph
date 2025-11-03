<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'author_id',
        'content',
        'category',
        'image'
    ];

    // Relationships
    public function author() {
        return $this->belongsTo(User::class, 'author_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function reactions() {
        return $this->hasMany(Reaction::class, 'post_id', 'post_id');
    }
}
