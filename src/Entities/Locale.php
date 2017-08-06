<?php namespace Arcanedev\Localization\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * Class     Locale
 *
 * @package  Arcanedev\Localization\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  key
 * @property  string  name
 * @property  string  script
 * @property  string  direction
 * @property  string  native
 * @property  string  regional
 * @property  array   extras
 */
class Locale extends Fluent
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Locale constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $keys = ['key', 'name', 'script', 'direction', 'native', 'regional'];

        parent::__construct(Arr::only($attributes, $keys) + [
            'extras' => Arr::except($attributes, $keys),
        ]);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a Locale instance.
     *
     * @param  string  $key
     * @param  array   $attributes
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public static function make($key, array $attributes)
    {
        return new static(compact('key') + $attributes);
    }

    /**
     * Get an extra attribute.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function extra($key, $default = null)
    {
        return Arr::get($this->attributes, "extras.{$key}", $default);
    }

    /**
     * Check if the locale is the default.
     *
     * @return bool
     */
    public function isDefault()
    {
        return config('app.locale') === $this->key;
    }

    /**
     * Check if the locale is supported.
     *
     * @return bool
     */
    public function isSupported()
    {
        return in_array($this->key, config('localization.supported-locales'));
    }
}
