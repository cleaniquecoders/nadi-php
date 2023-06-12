<?php

namespace CleaniqueCoders\Nadi\Transporter;

interface Contract
{
    public function configure(array $configurations = []): self;

    public function getTransporterId(): string;

    public function send(array $data);

    public function test();

    public function verify();
}
