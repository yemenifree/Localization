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
     * Load from config.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function loadFromConfig()
    {
        return $this->loadFromArray(config('localization.locales', []));
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
}
