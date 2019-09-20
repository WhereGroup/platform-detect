<?php
namespace Wheregroup\PlatformDetect;

/**
 * Platform family + bitness model.
 */
abstract class Platform
{
    const FAMILY_LINUX = 'LINUX';
    const FAMILY_WINDOWS = 'WIN';
    const FAMILY_MACOS = 'MAC';
    const FAMILY_BSD = 'BSD';
    const FAMILY_NIX = 'NIX';

    const ORDER_NIX = 'NIX';
    const ORDER_WINDOWS = 'WIN';

    const BITNESS_UNKNOWN = null;

    /** @var int */
    protected $bitness;
    
    /** @var string one of the FAMILY_ consts */
    protected $family;
    
    protected static $guessed = null;

    /**
     * @param string $family
     * @param int|null $bitness
     */
    public function __construct($family, $bitness = null)
    {
        if (!in_array($family, static::validFamilies(), true)) {
            throw new \InvalidArgumentException("Unknown family " . var_export($family, true));
        }
        $bitness = intval($bitness) ?: static::BITNESS_UNKNOWN;
        $this->family = $family;
        $this->bitness = $bitness;
    }

    /**
     * @return static
     */
    public static function guess()
    {
        if (static::$guessed === null) {
            switch (PHP_INT_SIZE) {
                default:
                    throw new \LogicException("Don't know what to do with PHP_INT_SIZE '" . PHP_INT_SIZE . "'");
                case 4:
                    $bitness = 32;
                    break;
                case 8:
                    $bitness = 64;
                    break;
            }
            /**
             * @see https://gist.github.com/asika32764/90e49a82c124858c9e1a
             */
            switch (substr(PHP_OS, 0, 7)) {
                default:
                    if (DIRECTORY_SEPARATOR === '/') {
                        $guessed = new Platform\Unixy(static::FAMILY_NIX, $bitness);
                    } else {
                        throw new \LogicException("Don't know what to make of PHP_OS '" . PHP_OS . "'");
                    }
                    break;
                case 'Linux':
                    $guessed = new Platform\Unixy(static::FAMILY_LINUX, $bitness);
                    break;
                case 'Darwin':
                    $guessed = new Platform\Unixy(static::FAMILY_MACOS, $bitness);
                    break;
                case 'FreeBSD':
                case 'OpenBSD':
                case 'NetBSD':
                    $guessed = new Platform\Unixy(static::FAMILY_BSD, $bitness);
                    break;
                /** @noinspection PhpMissingBreakStatementInspection */
                case 'WIN32':
                    $bitness = 32;
                    // fall through;
                case 'CYGWIN_':
                case 'Windows':
                case 'WINNT':
                    $guessed = new Platform\Windowsy(static::FAMILY_WINDOWS, $bitness);
                    break;
            }
            static::$guessed = $guessed;
        }
        return static::$guessed;
    }

    /**
     * It's like a family but higher up.
     * https://en.wikipedia.org/wiki/Taxonomic_rank
     *
     * @return string one of the ORDER_ consts
     */
    abstract public function getOrder();

    /**
     * @return string one of the FAMILY_ consts 
     */
    public function getFamily()
    {
        return $this->family;
    }
    
    /**
     * @return int
     */
    public function getBitness()
    {
        return $this->bitness;
    }

    /**
     * @return string[]
     */
    protected static function validFamilies()
    {
        return array(
            static::FAMILY_LINUX,
            static::FAMILY_NIX,
            static::FAMILY_WINDOWS,
            static::FAMILY_MACOS,
            static::FAMILY_BSD,
        );
    }
}
