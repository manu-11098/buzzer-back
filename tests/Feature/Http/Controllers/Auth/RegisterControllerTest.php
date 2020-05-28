<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function register_creates_user()
    {
        $user = [
            'name' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'nickname' => $this->faker->userName,
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->post('api/register', $user);

        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
            'nickname' => $user['nickname']
        ]);
    }

    /** @test */
    public function login_displays_validation_errors()
    {
        $response = $this->post('api/register', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name','lastname', 'email', 'nickname', 'password'
        ]);
    }
}
