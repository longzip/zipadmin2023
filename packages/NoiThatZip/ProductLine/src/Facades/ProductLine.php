<?php

namespace NoiThatZip\ProductLine\Facades;

use Illuminate\Support\Facades\Facade;

class ProductLine extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'productline';
    }
}
