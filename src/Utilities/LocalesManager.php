<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\Utilities\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Entities\Locale;
use Arcanedev\Localization\Entities\LocaleCollection;
use Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;

/**
 * Class     LocalesManager
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocalesManager implements LocalesManagerContract
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
    protected $defaultLocale;

    /**
     * Current locale.
     *
     * @var string
     */
    protected $current;

    /**
     * The supported locales (keys).
     *
     * @var array
     */
    protected $supported = [];

    /**
     * All the locales.
     *
     * @var \Arcanedev\Localization\Entities\LocaleCollection
     */
    protected $locales;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * LocalesManager constructor.
     */
    public function __construct()
    {
        $this->locales = LocaleCollection::make()->loadFromArray($this->getConfig('locales'));

        $this->setSupported($this->getConfig('supported-locales'));
        $this->setDefaultLocale();
    }

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
    public function setLocale($locale = null)
    {
        if (empty($locale) || ! is_string($locale)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $locale = request()->segment(1);
        }

        if ($this->isSupportedLocale($locale)) {
            $this->setCurrent($locale);
        }
        else {
            // if the first segment/locale passed is not valid the system would ask which locale have to take
            // it could be taken by the browser depending on your configuration
            $locale = null;
            $this->getCurrentOrDefault();
        }

        app()->setLocale($this->getCurrent());
        $this->updateRegional();

        return $locale;
    }

    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->defaultLocale;
    }

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     *
     * @return self
     */
    public function setDefaultLocale($locale = null)
    {
        if (is_null($locale))
            $locale = config('app.locale');

        $this->checkIfDefaultLocaleIsSupported($locale);
        $this->defaultLocale = $locale;

        return $this;
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrent()
    {
        if ( ! is_null($this->current))
            return $this->current;

        if ($this->useAcceptLanguageHeader())
            return $this->negotiateLocale();

        // Get application default language
        return $this->getDefault();
    }

    /**
     * Set the current locale.
     *
     * @param  string  $current
     *
     * @return self
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get the current locale entity.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity()
    {
        return $this->getSupportedLocales()->get($this->getCurrent());
    }

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales()
    {
        return $this->locales;
    }

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedKeys()
    {
        return $this->supported;
    }

    /**
     * Set the supported locales (keys).
     *
     * @param  array $keys
     *
     * @return \Arcanedev\Localization\Utilities\LocalesManager
     *
     * @throws \Arcanedev\Localization\Exceptions\UndefinedSupportedLocalesException
     */
    public function setSupported(array $keys)
    {
        if ( ! is_array($keys) || empty($keys))
            throw new UndefinedSupportedLocalesException(
                "The supported locales (keys) must be a valid array."
            );

        $this->supported = $keys;

        return $this;
    }

    /**
     * Get supported locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales()
    {
        return $this->locales->filter(function (Locale $locale) {
            return in_array($locale->key, $this->supported);
        });
    }

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
    public function isSupportedLocale($locale)
    {
        return $this->getSupportedLocales()->has($locale);
    }

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl()
    {
        return (bool) $this->getConfig('hide-default-in-url', false);
    }

    /**
     * Get current or default locale.
     *
     * @return string
     */
    public function getCurrentOrDefault()
    {
        // If we reached this point and isDefaultLocaleHiddenInUrl is true we have to assume we are routing
        // to a default locale route.
        if ($this->isDefaultLocaleHiddenInUrl())
            $this->setCurrent($this->getDefault());

        // But if isDefaultLocaleHiddenInUrl is false, we have to retrieve it from the browser...
        return $this->getCurrent();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Returns the translation key for a given path.
     *
     * @return bool
     */
    protected function useAcceptLanguageHeader()
    {
        return (bool) $this->getConfig('accept-language-header', true);
    }

    /**
     * Update locale regional.
     */
    protected function updateRegional()
    {
        $current = $this->getCurrentLocaleEntity();

        if ( ! empty($regional = $current->regional)) {
            setlocale(LC_TIME, "$regional.UTF-8");
            setlocale(LC_MONETARY, "$regional.UTF-8");
        }
    }

    /**
     * Check if default is supported.
     *
     * @param  string  $defaultLocale
     *
     * @throws \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     */
    protected function checkIfDefaultLocaleIsSupported($defaultLocale)
    {
        if ( ! $this->isSupportedLocale($defaultLocale))
            throw new UnsupportedLocaleException(
                "Laravel default locale [{$defaultLocale}] is not in the `supported-locales` array."
            );
    }

    /**
     * Get negotiated locale.
     *
     * @return string
     */
    protected function negotiateLocale()
    {
        return Negotiator::make($this->getDefault(), $this->getSupportedLocales())
                         ->negotiate(request());
    }

    /**
     * Get localization config.
     *
     * @param  string  $name
     * @param  mixed   $default
     *
     * @return mixed
     */
    private function getConfig($name, $default = null)
    {
        return config("localization.$name", $default);
    }
}
