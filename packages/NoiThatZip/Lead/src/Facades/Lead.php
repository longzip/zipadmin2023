<?php

namespace NoiThatZip\Lead\Facades;

use Illuminate\Support\Facades\Facade;

class Lead extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lead';
    }
}
