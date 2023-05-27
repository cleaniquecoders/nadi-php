<?php

namespace CleaniqueCoders\Nadi\Metric;

class Metric
{
    protected array $metrics = [];

    public function add(Contract $metric)
    {
        $this->metrics[] = $metric->toArray();
    }

    public function toArray(): array
    {
        return array_merge(
            $this->metrics,
            (new Browser)->toArray(),
            (new Network)->toArray(),
            (new OperatingSystem)->toArray(),
            (new Runtime)->toArray(),
            (new System)->toArray(),
        );
    }
}
