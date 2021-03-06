<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_authenticates_user()
    {
        $user = factory(User::class)->create();

        $this->post('api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_displays_validation_errors()
    {
        $response = $this->post('api/login', []);
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
