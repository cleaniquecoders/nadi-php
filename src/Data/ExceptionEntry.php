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
     * Assign the entry a family hash.
     *
     * @param  null|string  $familyHash
     * @return $this
     */
    public function setHashFamily($familyHash)
    {
        $this->hashFamily = md5(get_class($this->exception).
            $this->exception->getFile().
            $this->exception->getLine().
            $this->exception->getMessage().
            date('Y-m-d')
        );

        return $this;
    }
}
