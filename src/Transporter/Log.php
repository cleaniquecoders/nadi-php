<?php

namespace CleaniqueCoders\Nadi\Transporter;

class Log implements Contract
{
    protected $prefix = 'nadi-';

    protected $path;

    protected $configurations = [];

    public function configure(array $configurations = []): self
    {
        $this->configurations = $configurations;

        $path = isset($this->configurations['path']) ? $this->configurations['path'] : null;

        $this->setPath(
            empty($path) ? $this->defaultPath() : $path
        );

        return $this;
    }

    public function defaultPath()
    {
        return dirname(__FILE__, 3).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs';
    }

    public function setPath($path)
    {
        if (! file_exists($path)) {
            mkdir($path, 0770, true);
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getFileName()
    {
        return $this->prefix.date('Y-m-d').'.log';
    }

    public function getFilePath()
    {
        return $this->getPath().DIRECTORY_SEPARATOR.$this->getFileName();
    }

    public function send(array $data)
    {
        return $this->log('nadi.log', $data);
    }

    public function test()
    {
        return file_exists($this->getPath());
    }

    public function verify()
    {
        $this->log('nadi.verify');

        $path = $this->getFilePath();
        $content = file_get_contents($path);

        return file_exists($path) && strpos($content, 'nadi.verify');
    }

    public function log($key, $data = [])
    {
        $log = '['.date('Y-m-d H:i:s').'] - ' . $key . ' - ' . json_encode($data) . PHP_EOL;
        file_put_contents(
            $this->getFilePath(),
            $log,
            FILE_APPEND
        );
    }
}
