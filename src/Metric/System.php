<?php

namespace CleaniqueCoders\Nadi\Metric;

class System implements Contract
{
    public static function metrics(): array
    {
        // @todo need to cover any custom path in order to cover other frameworks.
        $directory = dirname(__FILE__, 3);

        // Laravel
        if (function_exists('base_path')) {
            $directory = base_path();
        }

        return [
            'system.server.cpu' => \sys_getloadavg(),
            'system.server.memory.peak' => \memory_get_peak_usage(true),
            'system.server.memory.usage' => \memory_get_usage(true),
            'system.server.storage' => \disk_total_space($directory),
        ];
    }
}
