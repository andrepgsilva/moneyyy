<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test for register of users
     *
     * @return void
     */
    public function testItCanRegisterAnUser()
    {
        $userAttributes = User::factory(['email_verified_at' => null])->raw();
        $userAttributes = [
            'name' => $userAttributes['name'],
            'email' => $userAttributes['email'],
            'password' => $userAttributes['password'],
            'password_confirmation' => $userAttributes['password'],
            'timezone' => $userAttributes['timezone'],
        ];

        $this->post(route('user.register'), $userAttributes)
            ->assertCreated();
    }

    /**
     * Test user creation without timezone
     *
     * @return void
     */
    public function testItCanCreateUserWithoutTimezone()
    {
        $userAttributes = User::factory(['email_verified_at' => null])->raw();
        $userAttributes = [
            'name' => $userAttributes['name'],
            'email' => $userAttributes['email'],
            'password' => $userAttributes['password'],
            'password_confirmation' => $userAttributes['password'],
        ];

        $response = $this->postJson(route('user.register'), $userAttributes);
        $response->assertStatus(422);
    }
}
