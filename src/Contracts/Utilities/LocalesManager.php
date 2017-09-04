<?php namespace Arcanedev\Localization\Contracts\Utilities;

/**
 * Interface  LocalesManager
 *
 * @package   Arcanedev\Localization\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface LocalesManager
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set and return current locale.
     *
     * @param  string|null  $locale
     *
     * @return string
     */
    public function setLocale($locale = null);

    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefault();

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     *
     * @return self
     */
    public function setDefaultLocale($locale = null);

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrent();

    /**
     * Set the current locale.
     *
     * @param  string  $locale
     *
     * @return self
     */
    public function setCurrent($locale);

    /**
     * Get the current locale entity.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity();

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales();

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedKeys();

    /**
     * Set the supported locales (keys).
     *
     * @param  array $keys
     *
     * @return \Arcanedev\Localization\Utilities\LocalesManager
     *
     * @throws \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function setSupported(array $keys);

    /**
     * Get supported locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if locale is supported.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    public function isSupportedLocale($locale);

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl();

    /**
     * Get current or default locale.
     *
     * @return string
     */
    public function getCurrentOrDefault();
}
