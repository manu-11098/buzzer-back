<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_nickname_returns_a_user()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('api/user/'.$user->nickname);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'description', 'email', 'nickname', 'followerCount', 'followingCount', 'likeCount']);
    }

    /** @test */
    public function put_user_updates_user()
    {
        $user = factory(User::class)->create();
        $user->name = 'Test';

        $response = $this->actingAs($user)->put('api/user/', $user->toArray());

        $response->assertStatus(200);
    }

    /** @test */
    public function get_nickname_searches_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/user/search/'.$user->nickname);

        $response->assertStatus(200);
        $this->assertEquals($user->nickname, $response[0]['nickname']);
    }

    /** @test */
    public function post_nickname_toogles_follow()
    {
        $user = factory(User::class)->create();
        $followed = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/user/'.$followed->nickname.'/toogleFollow');

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_follow_user', ['user_id' => $user->id, 'follow_id' => $followed->id]);
    }
}
