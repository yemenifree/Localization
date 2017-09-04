<?php namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Contracts\Utilities\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Contracts\Utilities\Negotiator as NegotiatorContract;
use Arcanedev\Localization\Contracts\Utilities\RouteTranslator as RouteTranslatorContract;
use Arcanedev\Localization\Utilities\LocalesManager;
use Arcanedev\Localization\Utilities\Negotiator;
use Arcanedev\Localization\Utilities\RouteTranslator;
use Arcanedev\Support\ServiceProvider;

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
        $this->registerLocalesManager();
        $this->registerNegotiator();
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

    /**
     * Register LocalesManager Utility.
     */
    private function registerLocalesManager()
    {
        $this->singleton(LocalesManagerContract::class, LocalesManager::class);
    }

    /**
     * Register Negotiator Utility.
     */
    private function registerNegotiator()
    {
        $this->bind(NegotiatorContract::class, function ($app) {
            /** @var  \Arcanedev\Localization\Contracts\Utilities\LocalesManager  $manager */
            $manager = $app[LocalesManagerContract::class];

            return new Negotiator(
                $manager->getDefault(),
                $manager->getSupportedLocales()
            );
        });
    }
}
