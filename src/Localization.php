<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\Localization as LocalizationContract;
use Arcanedev\Localization\Contracts\Utilities\RouteTranslator as RouteTranslatorContract;
use Arcanedev\Localization\Contracts\Utilities\LocalesManager as LocalesManagerContract;
use Arcanedev\Localization\Exceptions\UnsupportedLocaleException;
use Arcanedev\Localization\Utilities\Url;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Http\Request;

/**
 * Class     Localization
 *
 * @package  Arcanedev\Localization
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Localization implements LocalizationContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The RouteTranslator instance.
     *
     * @var \Arcanedev\Localization\Contracts\Utilities\RouteTranslator
     */
    protected $routeTranslator;

    /**
     * The LocalesManager instance.
     *
     * @var \Arcanedev\Localization\Contracts\Utilities\LocalesManager
     */
    private $localesManager;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Localization constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application                 $app
     * @param  \Arcanedev\Localization\Contracts\Utilities\RouteTranslator  $routeTranslator
     * @param  \Arcanedev\Localization\Contracts\Utilities\LocalesManager   $localesManager
     */
    public function __construct(
        ApplicationContract     $app,
        RouteTranslatorContract $routeTranslator,
        LocalesManagerContract  $localesManager
    ) {
        $this->routeTranslator = $routeTranslator;
        $this->localesManager  = $localesManager;
        $this->localesManager->setDefaultLocale();
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->localesManager->getDefault();
    }

    /**
     * Get the supported locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getSupportedLocales()
    {
        return $this->localesManager->getSupportedLocales();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate the route name.
     *
     * @param  string       $name
     * @param  string|null  $locale
     *
     * @return string
     */
    public function transRoute($name, $locale = null)
    {
        return $this->routeTranslator->trans($name, $locale);
    }

    /**
     * Set the supported locales.
     *
     * @param  array  $locales
     *
     * @return \Arcanedev\Localization\Localization
     */
    public function setSupportedLocales(array $locales)
    {
        $this->localesManager->setSupported($locales);

        return $this;
    }

    /**
     * Get supported locales keys.
     *
     * @return array
     */
    public function getSupportedLocalesKeys()
    {
        return $this->localesManager->getSupportedKeys();
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->localesManager->getCurrent();
    }

    /**
     * Returns current language.
     *
     * @return \Arcanedev\Localization\Entities\Locale
     */
    public function getCurrentLocaleEntity()
    {
        // TODO: Implement getCurrentLocaleEntity() method.
    }

    /**
     * Returns current locale name.
     *
     * @return string
     */
    public function getCurrentLocaleName()
    {
        // TODO: Implement getCurrentLocaleName() method.
    }

    /**
     * Returns current locale script.
     *
     * @return string
     */
    public function getCurrentLocaleScript()
    {
        // TODO: Implement getCurrentLocaleScript() method.
    }

    /**
     * Returns current locale direction.
     *
     * @return string
     */
    public function getCurrentLocaleDirection()
    {
        // TODO: Implement getCurrentLocaleDirection() method.
    }

    /**
     * Returns current locale native name.
     *
     * @return string
     */
    public function getCurrentLocaleNative()
    {
        // TODO: Implement getCurrentLocaleNative() method.
    }

    /**
     * Returns current locale regional.
     *
     * @return string
     */
    public function getCurrentLocaleRegional()
    {
        // TODO: Implement getCurrentLocaleRegional() method.
    }

    /**
     * Get all locales.
     *
     * @return \Arcanedev\Localization\Entities\LocaleCollection
     */
    public function getAllLocales()
    {
        // TODO: Implement getAllLocales() method.
    }

    /**
     * Set and return current locale.
     *
     * @param  string $locale
     *
     * @return string
     */
    public function setLocale($locale = null)
    {
        return $this->localesManager->setLocale($locale);
    }

    /**
     * Sets the base url for the site.
     *
     * @param  string $url
     */
    public function setBaseUrl($url)
    {
        // TODO: Implement setBaseUrl() method.
    }

    /**
     * Set route name from request.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function setRouteNameFromRequest(Request $request)
    {
        // TODO: Implement setRouteNameFromRequest() method.
    }

    /**
     * Returns an URL adapted to $locale or current locale.
     *
     * @param  string $url
     * @param  string|null $locale
     *
     * @return string
     */
    public function localizeURL($url = null, $locale = null)
    {
        return $this->getLocalizedURL($locale, $url);
    }

    /**
     * It returns an URL without locale (if it has it).
     *
     * @param  string|false $url
     *
     * @return string
     */
    public function getNonLocalizedURL($url = null)
    {
        // TODO: Implement getNonLocalizedURL() method.
    }

    /**
     * Returns an URL adapted to $locale.
     *
     * @param  string|null $locale
     * @param  string|null $url
     * @param  array $attributes
     * @param  bool|bool $showHiddenLocale
     *
     * @return string|false
     */
    public function getLocalizedURL($locale = null, $url = null, array $attributes = [], $showHiddenLocale = false)
    {
        // TODO: Implement getLocalizedURL() method.
    }

    /**
     * Create an url from the uri.
     *
     * @param  string $uri
     *
     * @return string
     */
    public function createUrlFromUri($uri)
    {
        // TODO: Implement createUrlFromUri() method.
    }

    /**
     * Returns an URL adapted to the route name and the locale given.
     *
     * @param  string|bool $locale
     * @param  string $transKey
     * @param  array $attributes
     * @param  bool|false $showHiddenLocale
     *
     * @return string|false
     */
    public function getUrlFromRouteName($locale, $transKey, array $attributes = [], $showHiddenLocale = false)
    {
        // TODO: Implement getUrlFromRouteName() method.
    }

    /**
     * Hide the default locale in URL ??
     *
     * @return bool
     */
    public function isDefaultLocaleHiddenInUrl()
    {
        return $this->localesManager->isDefaultLocaleHiddenInUrl();
    }

    /**
     * Check if Locale exists on the supported locales collection.
     *
     * @param  string|bool $locale
     *
     * @return bool
     */
    public function isLocaleSupported($locale)
    {
        return $this->localesManager->isSupportedLocale($locale);
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the locale is supported, otherwise throw an exception.
     *
     * @param  string  $locale
     *
     * @throws \Arcanedev\Localization\Exceptions\UnsupportedLocaleException
     */
    private function checkSupportedLocaleOrFail($locale)
    {
        if ( ! $this->isLocaleSupported($locale))
            throw new UnsupportedLocaleException(
                "Locale '{$locale}' is not in the list of supported locales."
            );
    }
}
