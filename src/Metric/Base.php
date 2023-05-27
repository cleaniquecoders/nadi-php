<?php

namespace CleaniqueCoders\Nadi\Metric;

use CleaniqueCoders\Nadi\Support\Arr;

abstract class Base implements Contract
{
    public function metrics(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return Arr::undot(
            $this->metrics()
        );
    }
}
