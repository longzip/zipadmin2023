<?php

namespace NoiThatZip\Kh15\Facades;

use Illuminate\Support\Facades\Facade;

class Kh15 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'kh15';
    }
}
