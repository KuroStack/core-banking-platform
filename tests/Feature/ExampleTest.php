<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_landing_page_loads_when_installed(): void
    {
        File::put(storage_path('installed'), now()->toDateTimeString());

        $this->get('/')->assertOk()->assertSee('CoopBank ERP');

        File::delete(storage_path('installed'));
    }

    public function test_redirects_to_installer_when_not_installed(): void
    {
        File::delete(storage_path('installed'));

        $this->get('/')->assertRedirect(route('install.welcome'));
    }
}
