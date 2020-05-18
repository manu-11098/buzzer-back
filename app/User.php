<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name', 'lastname', 'description', 'email', 'nickname', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyName()
    {
        return 'nickname';
    }

    public function buzzs() {
        return $this->hasMany(Buzz::class);
    }

    public function likes() {
        return $this->belongsToMany(Buzz::class, 'user_like_buzz', 'user_id', 'buzz_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow_user', 'user_id', 'follow_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow_user', 'follow_id', 'user_id');
    }
}
