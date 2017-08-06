<?php namespace Arcanedev\Localization\Tests;

use Arcanedev\Localization\Localization;

/**
 * Class     LocalizationTest
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalizationTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\Localization\Contracts\Localization */
    protected $localization;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->localization = $this->app->make(\Arcanedev\Localization\Contracts\Localization::class);
    }

    protected function tearDown()
    {
        unset($this->localization);

        parent::tearDown();
    }
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\Localization\Contracts\Localization::class,
            Localization::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->localization);
        }
    }

    /** @test */
    public function it_hello()
    {
        /** @var  \Arcanedev\Localization\Routing\Router  $router */
        $router = $this->app['router'];

        $response = $this->route('GET', 'en.index');

        dd(
            $response->getContent(),
            $router->getRoutes(),
            $this->routeRegistrar->getRouteNames()
        );
    }
}
