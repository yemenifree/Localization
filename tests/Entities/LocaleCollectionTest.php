<?php namespace Arcanedev\Localization\Tests\Entities;

use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Tests\TestCase;

/**
 * Class     LocaleCollectionTest
 *
 * @package  Arcanedev\Localization\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollectionTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Entities\LocaleCollection */
    private $locales;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->locales = new LocaleCollection;
    }

    public function tearDown()
    {
        unset($this->locales);

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
            \Illuminate\Support\Collection::class,
            LocaleCollection::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->locales);
        }
    }

    /** @test */
    public function it_can_get_all_locales()
    {
        $this->locales->loadFromArray($this->getRawLocales());
        $this->assertFalse($this->locales->isEmpty());

        $localesCount = $this->getRawLocalesCount();

        $this->assertGreaterThan(0, $localesCount);
        $this->assertCount($localesCount, $this->locales);
        $this->assertSame($localesCount, $this->locales->count());
    }

    /** @test */
    public function it_can_load_locales_from_config()
    {
        $this->locales->loadFromConfig();

        // Assert all locales
        $this->assertFalse($this->locales->isEmpty());
        $this->assertCount(289,  $this->locales);
        $this->assertSame(289, $this->locales->count());
    }

    /** @test */
    public function it_can_get_first_locale()
    {
        $this->locales->loadFromConfig();

        $locale = $this->locales->first();

        $this->assertInstanceOf(Locale::class, $locale);
        $this->assertSame('aa', $locale->key);
    }
}
