<?php

namespace NoiThatZip\Video\Facades;

use Illuminate\Support\Facades\Facade;

class Video extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'video';
    }
}
