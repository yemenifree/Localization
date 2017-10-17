<?php

namespace Arcanedev\Localization\Providers;

use Arcanedev\Localization\Middleware;
use Arcanedev\Localization\Routing\Router;
use Arcanedev\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class     RoutingServiceProvider.
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RoutingServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization-session-redirect' => Middleware\LocaleSessionRedirect::class,
        'localization-cookie-redirect'  => Middleware\LocaleCookieRedirect::class,
        'localization-redirect'         => Middleware\LocalizationRedirect::class,
        'localized-routes'              => Middleware\LocalizationRoutes::class,
        'translation-redirect'          => Middleware\TranslationRedirect::class,
    ];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['router']->mixin(new Router());

        parent::register();
    }
}
