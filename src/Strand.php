<?php

namespace Twist;

/**
 * Strand
 *
 * This class is used to manage a collection of providers.
 * It allows setting, adding, and retrieving providers based on conditions.
 */
class Strand
{
    private static $providers = [];

    public static function setProviders(array $provider)
    {
        static::$providers = $provider;

        return new static;
    }

    public function addProvider(callable $condition, string|array $provider)
    {
        if ($condition()) {
            static::$providers = array_merge(static::$providers, (array)$provider);
        }

        return $this;
    }

    public function getProviders()
    {
        return static::$providers;
    }
}
