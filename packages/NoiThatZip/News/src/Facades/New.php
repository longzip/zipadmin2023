<?php

namespace NoiThatZip\News\Facades;

use Illuminate\Support\Facades\Facade;

class New extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'new';
    }
}
