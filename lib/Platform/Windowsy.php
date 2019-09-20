<?php
namespace Wheregroup\PlatformDetect\Platform;

use Wheregroup\PlatformDetect\Platform;

class Windowsy extends Platform
{
    /**
     * @return string
     */
    public function getOrder()
    {
        return static::ORDER_WINDOWS;
    }

    /**
     * @return string[]
     */
    protected static function validFamilies()
    {
        return array(
            static::FAMILY_WINDOWS,
        );
    }
}
