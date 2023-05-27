<?php

namespace CleaniqueCoders\Nadi\Metric;

class Network implements Contract
{
    public static function metrics(): array
    {
        return [
            'net.host.name' => \gethostname(),
        ];
    }
}
