<?php

namespace CleaniqueCoders\Nadi\Data;

class ExceptionEntry extends Entry
{
    /**
     * The underlying exception instance.
     *
     * @var \Throwable
     */
    public $exception;

    /**
     * Create a new incoming entry instance.
     *
     * @param  \Throwable  $exception
     * @param  string  $type
     * @return void
     */
    public function __construct($exception, $type, array $content)
    {
        $this->exception = $exception;

        parent::__construct($type, $content);
    }

    /**
     * Determine if the incoming entry is an exception.
     *
     * @return bool
     */
    public function isException()
    {
        return true;
    }

    /**
     * Calculate the family look-up hash for the incoming entry.
     *
     * @return string
     */
    public function familyHash()
    {
        return md5(
            $this->exception->getFile().
            $this->exception->getLine()
        );
    }
}
