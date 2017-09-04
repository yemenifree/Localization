<?php namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\LocalesManager;

/**
 * Class     LocalesManagerTest
 *
 * @package  Arcanedev\Localization\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManagerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Contracts\Utilities\LocalesManager */
    private $localesManager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->localesManager = app(\Arcanedev\Localization\Contracts\Utilities\LocalesManager::class);
    }

    public function tearDown()
    {
        unset($this->localesManager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(LocalesManager::class, $this->localesManager);
    }

    /** @test */
    public function it_can_set_and_get_current_locale()
    {
        foreach ($this->getSupportedLocales() as $locale) {
            $this->localesManager->setCurrent($locale);

            $this->assertSame($locale, $this->localesManager->getCurrent());
        }
    }

    /** @test */
    public function it_can_get_current_locale_entity()
    {
        foreach ($this->getSupportedLocales() as $locale) {
            $this->localesManager->setCurrent($locale);
            $localeEntity = $this->localesManager->getCurrentLocaleEntity();

            $this->assertInstanceOf(Locale::class, $localeEntity);
            $this->assertSame($locale, $localeEntity->key);
        }
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $locales = $this->localesManager->getAllLocales();

        $this->assertInstanceOf(LocaleCollection::class, $locales);
        $this->assertFalse($locales->isEmpty());
        $this->assertCount(289, $locales);
        $this->assertSame(289, $locales->count());
    }

    /** @test */
    public function it_can_get_supported_locales()
    {
        $supportedLocales = $this->localesManager->getSupportedLocales();

        $this->assertInstanceOf(LocaleCollection::class, $supportedLocales);
        $this->assertFalse($supportedLocales->isEmpty());

        $supportedLocalesCount = count($this->getSupportedLocales());

        $this->assertCount($supportedLocalesCount, $supportedLocales);
        $this->assertSame($supportedLocalesCount, $supportedLocales->count());
    }

    /** @test */
    public function it_can_set_and_get_supported_locales()
    {
        $this->localesManager->setSupported($supported = ['en', 'fr']);
        $supportedLocales = $this->localesManager->getSupportedLocales();

        $this->assertFalse($supportedLocales->isEmpty());
        $this->assertCount(2, $supportedLocales);
        $this->assertSame(2, $supportedLocales->count());

        foreach ($supported as $locale) {
            $this->assertTrue($supportedLocales->has($locale));
        }
    }

    /** @test */
    public function it_can_get_supported_locales_keys()
    {
        $supportedKeys = $this->localesManager->getSupportedKeys();

        $this->assertCount(count($this->getSupportedLocales()), $supportedKeys);
        $this->assertSame($this->getSupportedLocales(), $supportedKeys);
    }

    /** @test */
    public function it_can_get_current_locale_without_negotiator()
    {
        $this->app['config']->set('localization.accept-language-header', false);

        foreach ($this->getSupportedLocales() as $locale) {
            $this->app['config']->set('app.locale', $locale);
            $this->localesManager = new LocalesManager;
            $this->assertSame($locale, $this->localesManager->getCurrent());
        }
    }
    /** @test */
    public function it_can_get_default_or_current_locale()
    {
        $this->app['config']->set('localization.hide-default-in-url', false);

        $this->localesManager = (new LocalesManager)->setCurrent('fr');

        $this->assertSame('en', $this->localesManager->getDefault());
        $this->assertSame('fr', $this->localesManager->getCurrent());
        $this->assertSame('fr', $this->localesManager->getCurrentOrDefault());

        $this->app['config']->set('localization.hide-default-in-url', true);

        $this->localesManager = (new LocalesManager)->setCurrent('fr');

        $this->assertSame('en', $this->localesManager->getDefault());
        $this->assertSame('fr', $this->localesManager->getCurrent());
        $this->assertSame('en', $this->localesManager->getCurrentOrDefault());
    }

    /** @test */
    public function it_can_set_and_get_default_locale()
    {
        foreach ($this->getSupportedLocales() as $locale) {
            $this->localesManager->setDefaultLocale($locale);

            $this->assertSame($locale, $this->localesManager->getDefault());
        }
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     * @expectedExceptionMessage Laravel default locale [jp] is not in the `supported-locales` array.
     */
    public function it_must_throw_unsupported_locale_exception_on_set_default_locale()
    {
        $this->localesManager->setDefaultLocale('jp');
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     * @expectedExceptionMessage  The supported locales (keys) must be a valid array.
     */
    public function it_must_throw_undefined_supported_locales_exception_on_set_with_empty_array()
    {
        $this->localesManager->setSupported([]);
    }
}
