<?php

namespace App\Http\Controllers;

use App\Buzz;
use App\User;

class BuzzController extends Controller
{
    public function buzzsByUser(User $user) {
        return Buzz::where('user_id', '=', $user->id)->with('user')->get();
    }
}
