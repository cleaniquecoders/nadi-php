<?php

namespace CleaniqueCoders\Nadi\Metric;

class Metric
{
    protected array $metrics = [];

    public function add(Contract $contract)
    {
        $this->metrics[] = $contract->metrics();
    }

    public function toArray(): array
    {
        return array_merge(
            $this->metrics,
            Browser::metrics(),
            Http::metrics(),
            Network::metrics(),
            OperatingSystem::metrics(),
            Runtime::metrics(),
            System::metrics(),
        );
    }
}
