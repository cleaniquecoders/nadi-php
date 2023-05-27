<?php

namespace CleaniqueCoders\Nadi\Metric;

class Runtime implements Contract
{
    public static function metrics(): array
    {
        return [
            'runtime.name' => 'PHP',
            'runtime.version' => \phpversion(),
        ];
    }
}
