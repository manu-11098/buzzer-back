<?php

namespace App\Http\Controllers;

use App\Buzz;
use App\User;
use Illuminate\Support\Facades\DB;

class BuzzController extends Controller
{
    public function feed() {
        return DB::table('buzzs')
                ->join('users', 'buzzs.user_id', '=', 'users.id')
                ->whereIn('buzzs.user_id', auth()->user()->followings()->pluck('id'))
                ->orderBy('buzzs.created_at', 'desc')
                ->get(['buzzs.id', 'buzzs.body', 'buzzs.created_at', 'users.nickname']);
    }

    public function buzzsByUser(User $user) {
        return DB::table('buzzs')
            ->join('users', 'buzzs.user_id', '=', 'users.id')
            ->where('buzzs.user_id', $user->id)
            ->orderBy('buzzs.created_at', 'desc')
            ->get(['buzzs.id', 'buzzs.body', 'buzzs.created_at', 'users.nickname']);
    }
}
