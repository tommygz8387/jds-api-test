<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function mypost() : HasMany
    {
        return $this->hasMany(News::class,'user_id','id');
    }

    public function mycomments() : HasMany
    {
        return $this->hasMany(Comment::class,'user_id','id');
    }

    public static function getRoleEnumValues()
    {
        return ['admin', 'member'];
    }

    public static function rules()
    {
        return [
            'role' => [
                'required',
                Rule::in(self::getRoleEnumValues()), // Validasi bahwa nilai harus ada dalam enum
            ],
        ];
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    
}
