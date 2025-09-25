<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    // Opción B: tras login, ir al panel
    public const HOME = '/admin/claims';
}

