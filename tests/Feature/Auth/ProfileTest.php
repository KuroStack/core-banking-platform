<?php

namespace Tests\Feature\Auth;

use App\Models\{User, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $role = Role::where('name', 'Clerk')->first();
        $this->user = User::create([
            'name' => 'Test User', 'email' => 'test@coopbank.com',
            'password' => Hash::make('OldPassword@123'), 'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_profile_page_loads(): void
    {
        $this->actingAs($this->user)->get('/profile')
            ->assertOk()
            ->assertSee('My Profile')
            ->assertSee('test@coopbank.com')
            ->assertSee('Change Password');
    }

    public function test_can_change_password(): void
    {
        $this->actingAs($this->user)->put('/profile/password', [
            'current_password'      => 'OldPassword@123',
            'password'              => 'NewPassword@456',
            'password_confirmation' => 'NewPassword@456',
        ])->assertRedirect()
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword@456', $this->user->fresh()->password));
    }

    public function test_wrong_current_password_rejected(): void
    {
        $this->actingAs($this->user)->put('/profile/password', [
            'current_password'      => 'WrongPassword',
            'password'              => 'NewPassword@456',
            'password_confirmation' => 'NewPassword@456',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_password_requires_confirmation(): void
    {
        $this->actingAs($this->user)->put('/profile/password', [
            'current_password'      => 'OldPassword@123',
            'password'              => 'NewPassword@456',
            'password_confirmation' => 'DifferentPassword',
        ])->assertSessionHasErrors('password');
    }

    public function test_password_minimum_length(): void
    {
        $this->actingAs($this->user)->put('/profile/password', [
            'current_password'      => 'OldPassword@123',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');
    }

    public function test_profile_requires_auth(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }
}
