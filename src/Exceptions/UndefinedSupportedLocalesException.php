<?php

namespace Arcanedev\Localization\Exceptions;

/**
 * Class     UndefinedSupportedLocalesException.
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UndefinedSupportedLocalesException extends LocalizationException
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        parent::__construct('Supported locales must be defined.');
    }
}
