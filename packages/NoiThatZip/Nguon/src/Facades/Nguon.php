<?php

namespace NoiThatZip\Nguon\Facades;

use Illuminate\Support\Facades\Facade;

class Nguon extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nguon';
    }
}
