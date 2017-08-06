<?php namespace Arcanedev\Localization\Tests\Entities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Tests\TestCase;
use League\Flysystem\Adapter\Local;

/**
 * Class     LocaleTest
 *
 * @package  Arcanedev\Localization\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        foreach ($this->getRawLocales() as $key => $attributes) {
            $locale = new Locale(
                $attributes = compact('key') + $attributes
            );

            $this->assertLocaleInstance($locale);
            $this->assertLocaleAttributes($locale, $attributes['key'], $attributes);
        }
    }

    /** @test */
    public function it_can_make_locale()
    {
        foreach ($this->getRawLocales() as $key => $attributes) {
            $locale = Locale::make($key, $attributes);

            $this->assertLocaleAttributes($locale, $key, $attributes);
        }
    }

    /** @test */
    public function it_can_get_attributes()
    {
        $locale = Locale::make('en', $attributes = $this->getRawLocale('en'));
        $this->assertLocaleAttributes($locale, 'en', $attributes);

        // Not available
        $this->assertNull($locale->get('timezone'));
        $this->assertSame('UTC', $locale->get('timezone', 'UTC'));
    }

    /** @test */
    public function it_can_get_extra_attribute()
    {
        $extras     = ['timezone' => 'UTC'];
        $attributes = $this->getRawLocale('en') + $extras;

        $locale = Locale::make('en', $attributes);

        $this->assertLocaleAttributes($locale, 'en', $attributes, $extras);

        $this->assertSame($extras['timezone'], $locale->extra('timezone'));
        $this->assertNull($locale->extra('currency'));
        $this->assertSame('GBP', $locale->extra('currency', 'GBP'));
    }

    /** @test */
    public function it_can_check_is_default()
    {
        $key = config('app.locale');

        $this->assertTrue(
            Locale::make($key, $this->getRawLocale($key))->isDefault()
        );

        $key = 'zu';

        $this->assertFalse(
            Locale::make($key, $this->getRawLocale($key))->isDefault()
        );
    }

    /** @test */
    public function it_can_check_if_supported()
    {
        foreach ($this->getSupportedLocales() as $key) {
            $this->assertTrue(
                Locale::make($key, $this->getRawLocale($key))->isSupported()
            );
        }

        $key = 'zu';

        $this->assertFalse(
            Locale::make($key, $this->getRawLocale($key))->isSupported()
        );
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the locale instance.
     *
     * @param  mixed  $locale
     */
    protected function assertLocaleInstance($locale)
    {
        $expectations = [
            \Illuminate\Support\Fluent::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Arcanedev\Localization\Entities\Locale::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $locale);
        }
    }

    /**
     * Assert the locale attributes.
     *
     * @param  \Arcanedev\Localization\Entities\Locale  $locale
     * @param  string                                   $key
     * @param  array                                    $attributes
     * @param  array                                    $extras
     */
    protected function assertLocaleAttributes($locale, $key, array $attributes, array $extras = [])
    {
        $message = "Failed on locale [{$key}]";

        $this->assertSame($key, $locale->get('key'), $message);
        $this->assertSame($attributes['name'], $locale->get('name'), $message);
        $this->assertSame($attributes['script'], $locale->get('script'), $message);
        $this->assertSame($attributes['direction'], $locale->get('direction'), $message);
        $this->assertSame($attributes['native'], $locale->get('native'), $message);
        $this->assertSame($attributes['regional'], $locale->get('regional'), $message);
        $this->assertSame($extras, $locale->get('extras'), $message);
    }
}
