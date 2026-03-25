<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_loads(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('Reset your password')
            ->assertSee('Send Reset Link');
    }

    public function test_forgot_password_validates_email(): void
    {
        $this->post('/forgot-password', ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');
    }

    public function test_forgot_password_requires_email(): void
    {
        $this->post('/forgot-password', [])
            ->assertSessionHasErrors('email');
    }
}
