<?php

namespace NoiThatZip\Attachment\Facades;

use Illuminate\Support\Facades\Facade;

class Attachment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'attachment';
    }
}
