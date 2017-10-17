<?php

namespace Arcanedev\Localization\Facades;

use Arcanedev\Localization\Contracts\Localization as LocalizationContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     Localization.
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Localization extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LocalizationContract::class;
    }
}
