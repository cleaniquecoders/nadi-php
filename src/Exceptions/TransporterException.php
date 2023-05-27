<?php

namespace CleaniqueCoders\Nadi\Exceptions;

class TransporterException extends \Exception
{
    public static function throwIfMissingKey($key = null)
    {
        if (empty($key)) {
            throw new self('Missing API Token');
        }
    }

    public static function throwIfMissingApplicationToken($token = null)
    {
        if (empty($token)) {
            throw new self('Missing Application Token');
        }
    }

    public static function throwIfMissingCredentials($key = null, $token = null)
    {
        self::throwIfMissingKey($key);
        self::throwIfMissingApplicationToken($token);
    }
}
