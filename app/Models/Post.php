<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PostLike;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'created_at',
        'updated_at'
    ];

    public function getTotalLikesAttribute()
    {
        return $this->hasMany(PostLike::class)->count();
    }
}
