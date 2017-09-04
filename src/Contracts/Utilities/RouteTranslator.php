<?php namespace Arcanedev\Localization\Contracts\Utilities;

/**
 * Interface  RouteTranslator
 *
 * @package   Arcanedev\Localization\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RouteTranslator
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string       $name
     * @param  string|null  $locale
     *
     * @return string
     */
    public function trans($name, $locale = null);
}
