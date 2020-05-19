<?php

namespace App\Http\Controllers;

use App\Buzz;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        } else if (auth()->user()->followings->contains($user)) {
            $user->followed = auth()->user()->followings->contains($user);
        }

        $user->followerCount = $user->followers()->count();
        $user->followingCount = $user->followings()->count();
        $user->likeCount = DB::table('buzzs')
                            ->join('user_like_buzz', 'buzzs.id', '=', 'user_like_buzz.buzz_id')
                            ->where('buzzs.user_id', $user->id)
                            ->count();

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

    public function search(String $search = '')
    {
        // TODO: This send the auth user, needs to remove o except
        return User::where('nickname', 'LIKE', "%$search%")->get();
    }

    public function followers(User $user)
    {
        return $user->followers;
    }

    public function followings(User $user)
    {
        return $user->followings;
    }

    public function toogleFollow(User $user)
    {
        if (auth()->user()->followings->contains($user)) {
            return auth()->user()->followings()->detach($user->id);
        }

        return auth()->user()->followings()->attach($user->id);
    }
}
