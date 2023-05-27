<?php

namespace CleaniqueCoders\Nadi\Metric;

class OperatingSystem implements Contract
{
    public static function metrics(): array
    {
        return [
            'os.name' => \php_uname('s'),
            'os.hostname' => \php_uname('n'),
            'os.release' => \php_uname('r'),
            'os.family' => PHP_OS_FAMILY,
            'os.version' => \php_uname('v'),
            'os.type' => \php_uname('m'),
        ];
    }
}
