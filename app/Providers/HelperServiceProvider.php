<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\TextHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('texthelper', function () {
            return new TextHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
