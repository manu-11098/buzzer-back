<?php

namespace App\Http\Controllers;

use App\Buzz;
use App\User;
use Illuminate\Http\Request;

class BuzzController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'body' => ['required', 'string', 'max:140']
        ]);

        auth()->user()->buzzs()->create($request->all());
    }

    public function show(Buzz $buzz) {
        return $this->loadRelatedData($buzz);
    }

    public function feed()
    {
        $buzzs = auth()->user()->followings->map(function ($user) {
            return $user->buzzs;
        })->collapse();

        $buzzs->each(function($buzz) {
            $buzz = $this->loadRelatedData($buzz);
        });

        return $buzzs->sortByDesc('created_at')->values()->all();
    }

    public function buzzsByUser(User $user)
    {
        $buzzs = $user->buzzs;

        $buzzs->each(function($buzz) {
            $buzz = $this->loadRelatedData($buzz);
        });

        return $buzzs->sortByDesc('created_at')->values()->all();
    }

    public function toogleLike(Buzz $buzz)
    {
        if (auth()->user()->likes->contains($buzz->id)) {
            return auth()->user()->likes()->detach($buzz->id);
        }

        return auth()->user()->likes()->attach($buzz->id);
    }

    protected function loadRelatedData(Buzz $buzz) {
        $buzz->user_nickname = $buzz->user()->get('nickname')->pluck('nickname')[0];
        $buzz->likeCount = $buzz->likes()->count();
        $buzz->liked = auth()->user()->likes->contains($buzz);

        return $buzz;
    }
}
