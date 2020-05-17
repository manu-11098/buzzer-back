<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function show(User $user = null) {
        if (! $user) {
            $user = auth()->user();
        } else if (auth()->user()->followings->contains($user)) {
            $user->followed = auth()->user()->followings->contains($user);
        }

        // TODO: Now we send the followers and followings in response, fix this to send just the number
        $user->followerCount = $this->getTotalFollowers($user);
        $user->followingCount = $this->getTotalFollowings($user);

        return $user;
    }

    public function followers(User $user) {
        return $user->followers;
    }

    public function getTotalFollowers($user) {
        return count($user->followers);
    }

    public function followings(User $user) {
        return $user->followings;
    }

    public function getTotalFollowings($user) {
        return count($user->followings);
    }

    public function toogleFollow(User $user) {
        if (auth()->user()->followings->contains($user)) {
            return auth()->user()->followings()->detach($user->id);
        }

        return auth()->user()->followings()->attach($user->id);
    }
}
