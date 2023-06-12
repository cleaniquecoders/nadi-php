<?php

namespace CleaniqueCoders\Nadi\Concerns;

trait InteractsWithTransporterId
{
    protected $transporter_id;

    public function getTransporterId(): string
    {
        if (! empty($this->transporter_id)) {
            return $this->transporter_id;
        }

        $result = '';
        $module_length = 40;
        $steps = round((64 / $module_length) + 0.5);

        for ($i = 0; $i < $steps; $i++) {
            $result .= sha1(uniqid().md5(rand()));
        }

        return $this->transporter_id = substr($result, 0, 64);
    }
}
