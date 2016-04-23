<?php

namespace Craigzearfoss\Bullets;

use Illuminate\Support\ServiceProvider;

class BulletsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../resources/migrations') => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }
}
