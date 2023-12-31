<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments";
    protected $primaryKey = "id";
    protected $fillable = [
        'content',
        'user_id',
        'news_id',
    ];

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function posted() :BelongsTo
    {
        return $this->belongsTo(News::class,'news_id','id');
    }
}
