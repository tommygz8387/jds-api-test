<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    use HasFactory;
    protected $table = "news";
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'photo',
        'content',
        'user_id',
    ];

    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class,'news_id','id');
    }
}
