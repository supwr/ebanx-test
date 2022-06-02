<?php

declare(strict_types=1);

namespace App\Utils;

trait Mapping
{
    /**
     * @param array $data
     * @param ...$keys
     * @return string
     */
    public static function getString(array $data, ...$keys): string
    {
        foreach ($keys as $key) {
            if(isset($data[$key])) {
                return (string) $data[$key];
            }
        }
        return '';
    }

    /**
     * @param array $data
     * @param ...$keys
     * @return int
     */
    public static function getInt(array $data, ...$keys): int
    {
        foreach ($keys as $key) {
            if(isset($data[$key])) {
                return (int) $data[$key];
            }
        }

        return 0;
    }

    /**
     * @param array $data
     * @param ...$keys
     * @return int|null
     */
    public static function getIntOrNull(array $data, ...$keys): ?int
    {
        foreach ($keys as $key) {
            if(isset($data[$key])) {
                return (int) $data[$key];
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param ...$keys
     * @return float
     */
    public static function getFloat(array $data, ...$keys): float
    {
        foreach ($keys as $key) {
            if(isset($data[$key])) {
                return (float) $data[$key];
            }
        }

        return 0;
    }
}
