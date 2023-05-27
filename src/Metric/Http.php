<?php

namespace CleaniqueCoders\Nadi\Metric;

class Http implements Contract
{
    public static function metrics(): array
    {
        return [
            'http.client.duration' => '',
            'http.scheme' => '',
            'http.route' => '',
            'http.method' => '',
            'http.status_code' => '',
            'http.query' => '',
            'http.uri' => '',
            'http.headers' => '',
        ];
    }
}
