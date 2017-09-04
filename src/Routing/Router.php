<?php namespace Arcanedev\Localization\Routing;

use Closure;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router as IlluminateRouter;

/**
 * Class     Router
 *
 * @package  Arcanedev\Localization\Routing
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Router extends IlluminateRouter
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string|null */
    private $locale;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add a localized group routes.
     *
     * @param  \Closure  $callback
     */
    public function transGroup(Closure $callback) {
        foreach (config('localization.supported-locales', []) as $locale) {
            $this->locale = $locale;

            $this->group(['prefix' => $locale, 'as' => "$locale.", 'middleware' => 'localization'], function ($router) use ($callback, $locale) {
                 $callback($router, $locale);
                 $this->cleanLocalizedRoutes($locale);
             });
        }
    }

    /**
     * Register a new translated GET route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transGet($trans, $action)
    {
        return $this->get(
            $this->transRoute($trans), $action
        );
    }

    /**
     * Register a new translated POST route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPost($trans, $action)
    {
        return $this->post(
            $this->transRoute($trans), $action
        );
    }

    /**
     * Register a new translated PUT route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPut($trans, $action)
    {
        return $this->put(
            $this->transRoute($trans), $action
        );
    }

    /**
     * Register a new translated PATCH route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transPatch($trans, $action)
    {
        return $this->patch(
            $this->transRoute($trans), $action
        );
    }

    /**
    -
     * Register a new translated DELETE route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transDelete($trans, $action)
    {
        return $this->delete(
            $this->transRoute($trans), $action
        );
    }

    /**
     * Register a new translated OPTIONS route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transOptions($trans, $action)
    {
        return $this->options(
            $this->transRoute($trans), $action
        );
    }

    /**
     * Register a new translated any route with the router.
     *
     * @param  string                 $trans
     * @param  \Closure|array|string  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function transAny($trans, $action)
    {
        return $this->any(
            $this->transRoute($trans), $action
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Clean the localized routes.
     *
     * @param  string  $locale
     */
    private function cleanLocalizedRoutes($locale)
    {
        $routes = new RouteCollection;

        foreach ($this->getRoutes()->getRoutes() as $route) {
            /** @var \Illuminate\Routing\Route $route */
            if ($route->named("$locale.")) {
                $route->setAction(array_except($route->getAction(), ['as']));
            }

            $routes->add($route);
        }

        $this->setRoutes($routes);
    }

    /**
     * Translate the route.
     *
     * @param  string  $key
     *
     * @return string
     */
    private function transRoute($key)
    {
        return localization()->transRoute($key, $this->locale);
    }
}
