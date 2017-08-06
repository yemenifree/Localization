<?php namespace Arcanedev\Localization\Contracts;

/**
 * Interface     Localization
 *
 * @package  Arcanedev\Localization\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Localization
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate the route name.
     *
     * @param  string  $name
     *
     * @return string
     */
    public function transRoute($name);
}
