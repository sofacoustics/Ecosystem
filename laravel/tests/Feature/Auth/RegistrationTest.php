<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
		{
				$email = 'test@example.com';
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => "$email",
            'password' => 'password',
						'password_confirmation' => 'password',
						'terms' => 1,
				]);

				$newUser = User::where('email', "$email")->firstOrFail();

        $this->assertAuthenticated();
        $response->assertRedirect("/profile?user=$newUser->id");
    }
}
