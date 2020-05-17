<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function show(User $user = null) {
        if (! $user) {
            return auth()->user();
        }

        $user->followed = auth()->user()->followings->contains($user);
        return $user;
    }

    public function followers(User $user) {
        return $user->followers;
    }

    public function followings(User $user) {
        return $user->followings;
    }
}
