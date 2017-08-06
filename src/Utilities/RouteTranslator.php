<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\Utilities\RouteTranslator as RouteTranslatorContract;
use Arcanedev\Localization\Exceptions\InvalidTranslationException;
use Illuminate\Translation\Translator;

/**
 * Class     RouteTranslator
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTranslator implements RouteTranslatorContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    /**
     * Translated routes collection.
     *
     * @var array
     */
    protected $translatedRoutes = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create RouteTranslator instance.
     *
     * @param  \Illuminate\Translation\Translator  $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate routes and save them to the translated routes array (used in the localize route filter).
     *
     * @param  string       $route
     * @param  string|null  $locale
     *
     * @return string
     */
    public function trans($route, $locale = null)
    {
        if ( ! in_array($route, $this->translatedRoutes))
            $this->translatedRoutes[] = $route;

        return $this->translate($route, $locale);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  string  $locale
     *
     * @return string
     *
     * @throws \Arcanedev\Localization\Exceptions\InvalidTranslationException
     */
    private function translate($key, $locale = null)
    {
        if (is_null($locale))
            $locale = $this->translator->getLocale();

        $translation = $this->translator->trans($key, [], $locale);

        // @codeCoverageIgnoreStart
        if ( ! is_string($translation))
            throw new InvalidTranslationException(
                "The translation key [{$key}] for locale [{$locale}] should return a string value."
            );
        // @codeCoverageIgnoreEnd

        return (string) $translation;
    }
}
