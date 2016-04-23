<?php

/**
 * Part of the Bullets package.
 *
 * @package    Bullets
 * @version    0.0.0
 * @author     Craig Zearfoss
 * @license    MIT License
 * @copyright  (c) 2011-2016, Craig Zearfoss
 * @link       http://craigzearfoss.com
 */

namespace Craigzearfoss\Bullets;

use Illuminate\Support\ServiceProvider;

class BulletsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../resources/migrations') => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }
}
