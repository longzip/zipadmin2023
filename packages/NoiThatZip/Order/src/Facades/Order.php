<?php

namespace NoiThatZip\Order\Facades;

use Illuminate\Support\Facades\Facade;

class Order extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'order';
    }
}
