<?php namespace Arcanedev\Localization\Tests;

use Arcanedev\Localization\Entities\LocaleCollection;
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

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage  Laravel default locale [jp] is not in the `supported-locales` array.
     */
    public function it_must_throw_unsupported_locale_exception_on_default_locale()
    {
        $this->app['config']->set('app.locale', 'jp');

        new Localization(
            $this->app,
            $this->app[\Arcanedev\Localization\Contracts\Utilities\RouteTranslator::class],
            $this->app[\Arcanedev\Localization\Contracts\Utilities\LocalesManager::class]
        );
    }

    /** @test */
    public function it_can_set_and_get_supported_locales()
    {
        $supportedLocales = localization()->getSupportedLocales();

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count($this->getSupportedLocales()), $supportedLocales);

        foreach($this->getSupportedLocales() as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }

        localization()->setSupportedLocales($locales = ['en', 'fr']);

        $supportedLocales = localization()->getSupportedLocales();

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(count($locales), $supportedLocales);

        foreach($locales as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }
    }

    /**
     * @test
     *
     * @expectedException  \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function it_must_throw_undefined_supported_locales_exception_on_set_supported_locales_with_empty_array()
    {
        localization()->setSupportedLocales([]);
    }

    /** @test */
    public function it_can_get_supported_locales_keys()
    {
        $this->assertSame(
            $this->getSupportedLocales(),
            localization()->getSupportedLocalesKeys()
        );
    }

    /** @test */
    public function it_can_set_locale()
    {
        $this->assertSame(route('en.about'), 'http://localhost/en/about');

        $this->assertSame('es', localization()->setLocale('es'));
        $this->assertSame('es', localization()->getCurrentLocale());
        $this->assertSame(route('es.about'), 'http://localhost/es/acerca');

        $this->assertSame('en', localization()->setLocale('en'));
        $this->assertSame(route('en.about'), 'http://localhost/en/about');
        $this->assertNull(localization()->setLocale('de'));
        $this->assertSame('en', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_prevent_to_set_default_app_locale()
    {
        $this->assertSame('en', localization()->getDefaultLocale());
        $this->assertSame('en', localization()->getCurrentLocale());

        localization()->setLocale('es'); // TODO: Check if Sh*t

        $this->assertSame('en', localization()->getDefaultLocale());
        $this->assertSame('es', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_get_current_locale()
    {
        $this->assertSame('en', localization()->getCurrentLocale());
        $this->assertNotEquals('es', localization()->getCurrentLocale());
        $this->assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('es');

        $this->assertNotEquals('en', localization()->getCurrentLocale());
        $this->assertSame('es', localization()->getCurrentLocale());
        $this->assertNotEquals('fr', localization()->getCurrentLocale());

        localization()->setLocale('fr');

        $this->assertNotEquals('en', localization()->getCurrentLocale());
        $this->assertNotEquals('es', localization()->getCurrentLocale());
        $this->assertSame('fr', localization()->getCurrentLocale());
    }

    /** @test */
    public function it_can_localize_url()
    {
        $this->assertSame(
            $this->baseUrl.'/'.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );

        // Missing trailing slash in a URL
        $this->assertSame(
            $this->baseUrl.'/'.localization()->getCurrentLocale(),
            localization()->localizeURL()
        );
        app('config')->set('localization.hide-default-in-url', true);
        // testing hide default locale option
        $this->assertNotEquals(
            $this->baseUrl.localization()->getDefaultLocale(),
            localization()->localizeURL()
        );
        $this->assertSame(
            $this->baseUrl,
            localization()->localizeURL()
        );
        localization()->setLocale('es');
        $this->assertSame(
            $this->baseUrl.'es',
            localization()->localizeURL()
        );
        $this->assertSame(
            $this->baseUrl.'about',
            localization()->localizeURL($this->baseUrl.'about', 'en')
        );
        $this->assertNotEquals(
            $this->baseUrl.'en/about',
            localization()->localizeURL($this->baseUrl.'about', 'en')
        );
        app('config')->set('localization.hide-default-in-url', false);
        $this->assertSame(
            $this->baseUrl.'en/about',
            localization()->localizeURL($this->baseUrl.'about', 'en')
        );
        $this->assertNotEquals(
            $this->baseUrl.'about',
            localization()->localizeURL($this->baseUrl.'about', 'en')
        );
    }

    /** @test */
    public function it_can_say_hello_from_translated_routes()
    {
        $expectations = [
            'en' => 'Hello world',
            'es' => 'Hola mundo',
            'fr' => 'Salut le monde',
        ];

        foreach ($expectations as $locale => $expected) {
            $this->assertSame($expected, $this->route('GET', "{$locale}.index")->getContent());
        }
    }
}
