<?php

namespace Tests\Feature\Http\Controllers;

use App\Buzz;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuzzControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function post_buzz_creates_buzz()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/buzz', ['body' => 'Test']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('buzzs', ['id' => 1, 'user_id' => $user->id, 'body' => 'Test']);
    }

    /** @test */
    public function get_feed()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('api/buzz/feed');

        $response->assertStatus(200);
    }

    /** @test */
    public function get_nickname_returns_user_buzzs()
    {
        $user = factory(User::class)->create();
        factory(Buzz::class)->create();

        $response = $this->actingAs($user)->get('api/buzz/searchByUser/'.$user->nickname);

        $response->assertStatus(200);
    }

    /** @test */
    public function get_id_returns_buzz()
    {
        $user = factory(User::class)->create();
        $buzz = factory(Buzz::class)->create();

        $response = $this->actingAs($user)->get('api/buzz/'.$buzz->id);

        $response->assertStatus(200);
        $this->assertEquals($buzz->body, $response['body']);
    }

    /** @test */
    public function post_id_toogles_buzz_like()
    {
        factory(User::class)->create();
        $buzz = factory(Buzz::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('api/buzz/'.$buzz->id.'/toogleLike');

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_like_buzz', ['user_id' => $user->id, 'buzz_id' => $buzz->id]);
    }
}
