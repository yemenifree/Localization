<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Utilities\RouteTranslator;
use Arcanedev\Support\ServiceProvider;
use Arcanedev\Localization\Contracts\Utilities\RouteTranslator as RouteTranslatorContract;

/**
 * Class     UtilitiesServiceProvider
 *
 * @package  Arcanedev\Localization\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UtilitiesServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function register()
    {
        parent::register();

        $this->registerRouteTranslator();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register RouteTranslator utility.
     */
    private function registerRouteTranslator()
    {
        $this->singleton(RouteTranslatorContract::class, function ($app) {
            return new RouteTranslator($app['translator']);
        });
    }
}
