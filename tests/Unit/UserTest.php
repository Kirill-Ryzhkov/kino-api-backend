<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
{
    public function test_get_user()
    {
        $this->signIn();
        $user = (new UserController)->get()->original['user'];
        $this->assertEquals(false, is_null($user));
    }

    public function signIn()
    {
        $this->actingAs(User::factory()->create());
    }
}
