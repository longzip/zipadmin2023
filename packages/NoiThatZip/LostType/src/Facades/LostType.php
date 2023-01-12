<?php

namespace NoiThatZip\LostType\Facades;

use Illuminate\Support\Facades\Facade;

class LostType extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'losttype';
    }
}
