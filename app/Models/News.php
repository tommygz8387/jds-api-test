<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $table = "news";
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'photo',
        'content',
        'user_id',
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'news_id','id');
    }
}