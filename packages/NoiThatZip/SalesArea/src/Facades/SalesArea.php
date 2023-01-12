<?php

namespace NoiThatZip\SalesArea\Facades;

use Illuminate\Support\Facades\Facade;

class SalesArea extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'salesarea';
    }
}
