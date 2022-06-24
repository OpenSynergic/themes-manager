<?php

namespace OpenSynergic\ThemesManager\Facades;

use Illuminate\Support\Facades\Facade;
use OpenSynergic\ThemesManager\ThemesManager;

/**
 * @see \OpenSynergic\ThemesManager\ThemesManager
 */
class Themes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ThemesManager::class;
    }
}
