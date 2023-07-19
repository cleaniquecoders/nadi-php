<?php

namespace CleaniqueCoders\Nadi\Transporter;

use CleaniqueCoders\Nadi\Concerns\InteractsWithTransporterId;

class Log implements Contract
{
    use InteractsWithTransporterId;

    protected $prefix = 'nadi-';

    protected $path;

    protected $configurations = [];

    protected $storage = [];

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
            file_put_contents($path.DIRECTORY_SEPARATOR.'.gitignore', '*'.PHP_EOL.'!.gitignore');
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

    public function store(array $data): self
    {
        array_push($this->storage, $data);

        return $this;
    }

    public function send()
    {
        $data = $this->storage;
        $filename = $this->getTransporterId();
        $filepath = $this->getPath().DIRECTORY_SEPARATOR.$filename.'.json';
        $content = json_encode($data, JSON_PRETTY_PRINT | JSON_OBJECT_AS_ARRAY);
        file_put_contents(
            $filepath,
            $content,
            FILE_APPEND
        );

        return true;
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
        $data['transporter_id'] = $this->getTransporterId();
        $log = '['.date('Y-m-d H:i:s').'] - '.$key.' - '.json_encode($data).PHP_EOL;
        file_put_contents(
            $this->getFilePath(),
            $log,
            FILE_APPEND
        );
    }
}
