<?php

namespace NoiThatZip\ActivityType\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityType extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'activitytype';
    }
}
