<?php

namespace Towoju5\Fixedfloat;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Towoju5\Fixedfloat\Skeleton\SkeletonClass
 */
class FixedfloatFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fixedfloat';
    }
}
