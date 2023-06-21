<?php

namespace CleaniqueCoders\Nadi\Metric;

class System extends Base
{
    public function metrics(): array
    {
        // @todo need to cover any custom path in order to cover other frameworks.
        $directory = dirname(__FILE__, 3);

        // Laravel
        if (function_exists('base_path')) {
            $directory = base_path();
        }

        return [
            'system.server.cpu' => $this->getCpu(),
            'system.server.memory.peak' => \memory_get_peak_usage(true),
            'system.server.memory.usage' => \memory_get_usage(true),
            'system.server.storage' => \disk_total_space($directory),
        ];
    }

    public function getCpu()
    {
        if (! strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return \sys_getloadavg();
        }

        if (! extension_loaded('com_dotnet')) {
            return [];
        }

        $wmi = new \COM('winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2');
        $query = 'SELECT LoadPercentage FROM Win32_Processor';
        $loadPercentage = $wmi->ExecQuery($query);

        $load = [];
        foreach ($loadPercentage as $processor) {
            $load[] = $processor->LoadPercentage;
        }

        return $load;
    }
}
