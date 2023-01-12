<?php

namespace NoiThatZip\Quote\Facades;

use Illuminate\Support\Facades\Facade;

class Quote extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'quote';
    }
}
