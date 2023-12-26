<?php

namespace SparkoutTech\Phonepe;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SparkoutTech\Phonepe\Skeleton\SkeletonClass
 */
class PhonepeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phonepe';
    }
}
