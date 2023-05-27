<?php

namespace CleaniqueCoders\Nadi\Metric;

class Runtime extends Base
{
    public function metrics(): array
    {
        return [
            'runtime.name' => 'PHP',
            'runtime.version' => \phpversion(),
        ];
    }
}
