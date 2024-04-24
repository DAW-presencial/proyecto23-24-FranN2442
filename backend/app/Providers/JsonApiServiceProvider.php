<?php

namespace App\Providers;

use App\JsonApi\JsonApiQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Builder::mixin(new JsonApiQuery());
    }
}
