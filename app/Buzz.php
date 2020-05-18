<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buzz extends Model
{
    protected $fillable = [
        'body'
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
