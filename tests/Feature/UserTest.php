<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;

class UserTest extends TestCase
{
    protected $seed = UserSeeder::class;
    public function test_get_user_response_code()
    {
        $this->signIn();
        $response = $this->get('/api/user');
        $response->assertStatus(200);
    }

    public function test_update_user_response_code()
    {
        $this->signIn();
        $response = $this->put('/api/user/update', [
            'name' => 'name',
            'email' => 'email' . time() . '@email.com',
            'password' => 'pass123temp'
        ]);
        $response->assertStatus(200);
    }

    public function test_logout_response_code()
    {
        $this->signIn();
        $response = $this->post('/api/user/logout');
        $response->assertStatus(200);
    }

    public function test_user_seeder()
    {
        $this->signIn();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com'
        ]);
    }

    public function signIn()
    {
        $this->actingAs(User::factory()->create());
    }
}
