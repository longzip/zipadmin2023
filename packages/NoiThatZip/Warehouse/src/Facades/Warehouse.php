<?php

namespace NoiThatZip\Warehouse\Facades;

use Illuminate\Support\Facades\Facade;

class Warehouse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'warehouse';
    }
}
