<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Ensures the app can boot for the installer even without .env.
 * Auto-creates .env from .env.example if missing.
 */
class EnsureInstallable
{
    public function handle(Request $request, Closure $next)
    {
        // If already installed, redirect away from installer
        if (File::exists(storage_path('installed'))) {
            return redirect('/');
        }

        // Auto-create .env from .env.example if missing
        if (!File::exists(base_path('.env')) && File::exists(base_path('.env.example'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
        }

        return $next($request);
    }
}
