<?php

namespace Tests\Feature\Api;

use App\Models\{User, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $role = Role::where('name', 'Clerk')->first();
        $this->user = User::create([
            'name' => 'API User', 'email' => 'api@test.com',
            'password' => Hash::make('OldPass@123'), 'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_me_endpoint(): void
    {
        Sanctum::actingAs($this->user);
        $this->getJson('/api/me')
            ->assertOk()
            ->assertJsonFragment(['email' => 'api@test.com']);
    }

    public function test_me_requires_auth(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }
}
