<?php

namespace CleaniqueCoders\Nadi\Transporter;

interface Contract
{
    public function configure(array $configurations = []): self;

    public function getTransporterId(): string;

    public function store(array $data): self;

    public function send();

    public function test();

    public function verify();
}
