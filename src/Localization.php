<?php namespace Arcanedev\Localization;

use Arcanedev\Localization\Contracts\Localization as LocalizationContract;
use Arcanedev\Localization\Contracts\Utilities\RouteTranslator;

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

    /** @var  \Arcanedev\Localization\Contracts\Utilities\RouteTranslator */
    protected $routeTranslator;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Localization constructor.
     *
     * @param  \Arcanedev\Localization\Contracts\Utilities\RouteTranslator  $routeTranslator
     */
    public function __construct(RouteTranslator $routeTranslator)
    {
        $this->routeTranslator = $routeTranslator;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Translate the route name.
     *
     * @param  string $name
     *
     * @return string
     */
    public function transRoute($name)
    {
        return $this->routeTranslator->trans($name);
    }
}
