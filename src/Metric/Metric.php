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
        $metrics = array_merge(
            (new Browser)->toArray(),
            (new Network)->toArray(),
            (new OperatingSystem)->toArray(),
            (new Runtime)->toArray(),
            (new System)->toArray(),
        );

        foreach ($this->metrics as $metric) {
            $metrics = array_merge($metrics, $metric);
        }

        return $metrics;
    }
}
