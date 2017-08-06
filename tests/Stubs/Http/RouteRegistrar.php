<?php namespace Arcanedev\Localization\Tests\Stubs\Http;

/**
 * Class     RouteRegistrar
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @mixin \Arcanedev\Localization\Routing\Router
 */
class RouteRegistrar extends \Arcanedev\Support\Routing\RouteRegistrar
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var array */
    protected $routeNames = [];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Map the routes for the application.
     */
    public function map()
    {
        $this->group(['middleware' => 'web'], function () {
            $this->transGroup(function () {
                $this->get('/', [
                    'as' =>  'index',
                    function () {
                        return app('translator')->get('localization::routes.hello');
                    }
                ]);
                $this->setRouteName('index');

                $this->get('test', [
                    'as' => 'test',
                    function () {
                        return app('translator')->get('localization::routes.test-text');
                    }
                ]);
                $this->setRouteName('test');

                $this->transGet('localization::routes.about', [
                    'as' => 'about',
                    function () {
                        return localization()->getLocalizedURL('es') ?: 'Not url available';
                    }
                ]);
                $this->setRouteName('about');

                $this->transGet('localization::routes.view', [
                    'as' => 'view',
                    function () {
                        return localization()->getLocalizedURL('es') ?: 'Not url available';
                    }
                ]);
                $this->setRouteName('view');

                $this->transGet('localization::routes.view-project', [
                    'as' => 'view-project',
                    function () {
                        return localization()->getLocalizedURL('es') ?: 'Not url available';
                    }
                ]);
                $this->setRouteName('view-project');

                //  Other methods //

                $this->transPost('localization::routes.methods.post', [
                    'as' => 'method.post',
                    function () {
                        return 'POST method';
                    }
                ]);
                $this->setRouteName('method.post');

                $this->transPut('localization::routes.methods.put', [
                    'as' => 'method.put',
                    function () {
                        return 'PUT method';
                    }
                ]);
                $this->setRouteName('method.put');

                $this->transPatch('localization::routes.methods.patch', [
                    'as' => 'method.patch',
                    function () {
                        return 'PATCH method';
                    }
                ]);
                $this->setRouteName('method.patch');

                $this->transOptions('localization::routes.methods.options', [
                    'as' => 'method.options',
                    function () {
                        return 'OPTIONS method';
                    }
                ]);
                $this->setRouteName('method.options');

                $this->transDelete('localization::routes.methods.delete', [
                    'as' => 'method.delete',
                    function () {
                        return 'DELETE method';
                    }
                ]);
                $this->setRouteName('method.delete');

                $this->transAny('localization::routes.methods.any', [
                    'as' => 'method.any',
                    function () {
                        return 'Any method';
                    }
                ]);
                $this->setRouteName('method.any');

                //  Resource Controllers  //

                $this->resource('dummy', Controllers\DummyController::class);
                $this->setRouteNames([
                    'dummy.index', 'dummy.create', 'dummy.store', 'dummy.show',
                    'dummy.edit', 'dummy.update', 'dummy.destroy',
                ]);

                $this->group(['prefix'  => 'foo'], function () {
                    $this->resource('bar', Controllers\BarController::class);
                });

                $this->setRouteNames([
                    'bar.index', 'bar.create', 'bar.store', 'bar.show',
                    'bar.edit', 'bar.update', 'bar.destroy',
                ]);
            });
        });
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get route names collection.
     *
     * @return array
     */
    public function getRouteNames()
    {
        return $this->routeNames;
    }

    /**
     * Set route names to routes collection.
     *
     * @param  array  $names
     *
     * @return self
     */
    private function setRouteNames(array $names)
    {
        foreach ($names as $name) {
            $this->setRouteName($name);
        }

        return $this;
    }

    /**
     * Set route name to routes collection.
     *
     * @param  string  $name
     *
     * @return self
     */
    private function setRouteName($name)
    {
        if ( ! empty($name)) {
            $this->routeNames[] = $name;
        }

        return $this;
    }
}
