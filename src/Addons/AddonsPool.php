<?php

namespace Obelaw\Twist\Addons;

class AddonsPool
{
    const LEVELONE = '/*/twist.php';
    const LEVELTWO = '/**/*/twist.php';

    /**
     * Pool paths
     * 
     * @var array
     */
    private static $poolPaths = [];

    /**
     * Twist paths
     * 
     * @var array
     */
    private static $twistPaths = [];

    /**
     * Set new pool path
     *
     * @param string $path
     * @param string|null $level
     */
    public static function setPoolPath($path, $level = null): void
    {
        array_push(static::$poolPaths, [
            'level' => $level ?? self::LEVELTWO,
            'path' => $path
        ]);
    }

    /**
     * Check if the pool has paths
     */
    public static function hasPools(): bool
    {
        return (count(static::$poolPaths) != 0) ? true : false;
    }

    public static function loadTwist($twistPath)
    {
        array_push(static::$twistPaths, $twistPath);
    }

    /**
     * Scan the pool paths
     */
    public static function scan(): void
    {
        foreach (static::$poolPaths as $scan) {
            foreach (glob($scan['path'] . $scan['level']) as $obelaw) {
                require $obelaw;
            }
        }

        if (!empty(static::$twistPaths)) {
            foreach (static::$twistPaths as $twistPath) {
                require $twistPath;
            }
        }
    }

    public static function getPoolPaths(): array
    {
        return static::$poolPaths;
    }
}
