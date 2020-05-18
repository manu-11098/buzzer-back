<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        } else if (auth()->user()->followings->contains($user)) {
            $user->followed = auth()->user()->followings->contains($user);
        }

        // TODO: Now we send the followers and followings in response, fix this to send just the number
        $user->followerCount = $this->getTotalFollowers($user);
        $user->followingCount = $this->getTotalFollowings($user);

        return $user;
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->id],
            'nickname' => ['required', 'string', 'max:255', 'unique:users,nickname,' . auth()->user()->id]
        ]);

        if ($request->password) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            auth()->user()->password = bcrypt($request->password);
        }

        auth()->user()->name = $request->name;
        auth()->user()->lastname = $request->lastname;
        auth()->user()->description = $request->description;
        auth()->user()->email = $request->email;
        auth()->user()->nickname = $request->nickname;
        auth()->user()->save();
    }

    public function followers(User $user)
    {
        return $user->followers;
    }

    public function getTotalFollowers($user)
    {
        return count($user->followers);
    }

    public function followings(User $user)
    {
        return $user->followings;
    }

    public function getTotalFollowings($user)
    {
        return count($user->followings);
    }

    public function toogleFollow(User $user)
    {
        if (auth()->user()->followings->contains($user)) {
            return auth()->user()->followings()->detach($user->id);
        }

        return auth()->user()->followings()->attach($user->id);
    }
}
