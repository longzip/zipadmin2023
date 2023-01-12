<?php

namespace NoiThatZip\Lost\Facades;

use Illuminate\Support\Facades\Facade;

class Lost extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lost';
    }
}
