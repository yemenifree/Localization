<?php namespace Arcanedev\Localization\Entities;

use Illuminate\Support\Collection;

/**
 * Class     LocaleCollection
 *
 * @package  Arcanedev\Localization\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollection extends Collection
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Default locale.
     *
     * @var string
     */
    protected $default;

    /**
     * Supported locales.
     *
     * @var array
     */
    protected $supported = [];

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the default locale.
     *
     * @param  string  $default
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Set supported locales keys.
     *
     * @param  array  $supported
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function setSupportedKeys(array $supported)
    {
        $this->supported = $supported;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the first locale from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed          $default
     *
     * @return \Arcanedev\Localization\Entities\Locale|mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return parent::first($callback, $default);
    }

    /**
     * Get the default locale.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getDefault()
    {
        return $this->get($this->default);
    }

    /**
     * Get supported locales collection.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupported()
    {
        return $this->only($this->supported);
    }

    /**
     * Load from config.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function loadFromConfig()
    {
        return $this->setDefault(config('app.locale'))
                    ->loadFromArray(config('localization.locales', []))
                    ->setSupportedKeys(config('localization.supported-locales', []));
    }

    /**
     * Load locales from array.
     *
     * @param  array  $locales
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function loadFromArray(array $locales)
    {
        $this->items = []; // Reset all

        foreach ($locales as $key => $locale) {
            $this->put($key, Locale::make($key, $locale));
        }

        return $this;
    }

    /**
     * Partition the collection into two arrays (Supported & Unsupported locales).
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function partitionSupported()
    {
        return $this->partition(function (Locale $locale) {
            return in_array($locale->key, $this->supported);
        });
    }
}
