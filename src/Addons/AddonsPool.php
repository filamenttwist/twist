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
    }
}
