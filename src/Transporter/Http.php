<?php

namespace CleaniqueCoders\Nadi\Transporter;

use CleaniqueCoders\Nadi\Concerns\InteractsWithTransporterId;
use CleaniqueCoders\Nadi\Exceptions\TransporterException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Http implements Contract
{
    use InteractsWithTransporterId;

    const VERSION = 'v1';

    const ENDPOINT = 'https://nadi.cleaniquecoders.com/api';

    protected Client $client;

    protected string $endpoint;

    protected $configurations = [];

    protected $storage = [];

    public function configure(array $configurations = []): self
    {
        $this->configurations = $configurations;

        $key = isset($this->configurations['key']) ? $this->configurations['key'] : null;
        $token = isset($this->configurations['token']) ? $this->configurations['token'] : null;
        $version = isset($this->configurations['version']) ? $this->configurations['version'] : self::VERSION;
        $endpoint = isset($this->configurations['endpoint']) ? $this->configurations['endpoint'] : self::ENDPOINT;

        TransporterException::throwIfMissingCredentials($key, $token);

        $this->endpoint = $endpoint;

        $this->setClient(
            new Client([
                'headers' => [
                    'Accept' => 'application/vnd.nadi.'.$version.'+json',
                    'Authorization' => 'Bearer '.$key,
                    'Nadi-Token' => $token,
                    'Nadi-Transporter-Id' => $this->getTransporterId(),
                    'Content-Type' => 'application/json',
                ],
            ])
        );

        return $this;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function store(array $data): self
    {
        $this->storage[] = $data;

        return $this;
    }

    public function send()
    {
        return $this->client->post($this->url('record'), [RequestOptions::JSON => $this->storage]);
    }

    public function test()
    {
        $response = $this->client->post($this->url('test'));

        return $response->getStatusCode() == 200;
    }

    public function verify()
    {
        $response = $this->client->post($this->url('verify'));

        return $response->getStatusCode() == 200;
    }

    public function url(string $endpoint)
    {
        return rtrim($this->endpoint, '/').'/'.trim($endpoint, '/');
    }
}
