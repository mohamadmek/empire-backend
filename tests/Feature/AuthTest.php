<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $this->post('/api/auth/register', [
            'email' => 'john@live.com',
            'password' => 'password'
        ]);

        $this->assertCount(1, User::all());
    }

    public function test_user_can_login_with_correct_credentials()
    {
        User::factory()->create([
            'email' => 'john@live.com'
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => 'john@live.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        User::factory()->create([
            'email' => 'john@live.com'
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => 'john@live.com',
            'password' => 'incorrectpass'
        ]);

        $response->assertStatus(403);
    }
}