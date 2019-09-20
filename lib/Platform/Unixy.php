<?php
namespace Wheregroup\PlatformDetect\Platform;

use Wheregroup\PlatformDetect\Platform;

class Unixy extends Platform
{
    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return static::ORDER_NIX;
    }

    /**
     * @return string[]
     */
    protected static function validFamilies()
    {
        return array(
            static::FAMILY_LINUX,
            static::FAMILY_MACOS,
            static::FAMILY_BSD,
            static::FAMILY_NIX,
        );
    }
}
