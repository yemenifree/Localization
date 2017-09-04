<?php namespace Arcanedev\Localization\Tests;

use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\Localization\Tests\Stubs\Http\RouteRegistrar */
    protected $routeRegistrar;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Arcanedev\Localization\LocalizationServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Localization' => \Arcanedev\Localization\Facades\Localization::class,
        ];
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(\Illuminate\Contracts\Http\Kernel::class, Stubs\Http\Kernel::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /**
         * @var  \Illuminate\Contracts\Config\Repository         $config
         * @var  \Illuminate\Translation\Translator              $translator
         * @var  \Arcanedev\Localization\Contracts\Localization  $localization
         */
        $config       = $app['config'];
        $translator = $app['translator'];

        $config->set('app.debug', true);
        $config->set('app.url', $this->baseUrl);

        $config->set('localization.route.middleware', [
//            \Arcanedev\Localization\Middleware\LocaleSessionRedirect::class,
//            \Arcanedev\Localization\Middleware\LocaleCookieRedirect::class,
            \Arcanedev\Localization\Middleware\LocalizationRedirect::class,
            \Arcanedev\Localization\Middleware\LocalizationRoutes::class,
            \Arcanedev\Localization\Middleware\TranslationRedirect::class,
        ]);

        $translator->getLoader()->addNamespace(
            'localization',
            realpath(__DIR__).DS.'fixtures'.DS.'lang'
        );

        $translator->load('localization', 'routes', 'en');
        $translator->load('localization', 'routes', 'es');
        $translator->load('localization', 'routes', 'fr');

//        $localization->setBaseUrl($this->baseUrl);

        $this->setRoutes();
//        $this->setDatabase($config);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set routes for testing
     */
    protected function setRoutes()
    {
        $this->routeRegistrar = tap(new Stubs\Http\RouteRegistrar, function ($registrar) {
            $registrar->map();
        });
    }

    /**
     * Set the database.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    protected function setDatabase($config)
    {
        $config->set('database.default', 'testbench');
        $config->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }























    /**
     * Get the supported locales.
     *
     * @return array
     */
    protected function getSupportedLocales()
    {
        return $this->app['config']->get('localization.supported-locales', []);
    }

    /**
     * Get a raw locale data.
     *
     * @param  string  $key
     *
     * @return array
     */
    protected function getRawLocale($key)
    {
        return $this->app['config']->get("localization.locales.{$key}");
    }

    /**
     * Get the raw locales.
     *
     * @return array
     */
    protected function getRawLocales()
    {
        return $this->app['config']->get('localization.locales');
    }

    /**
     * Get the raw locales count.
     *
     * @return int
     */
    protected function getRawLocalesCount()
    {
        return count($this->getRawLocales());
    }
}
