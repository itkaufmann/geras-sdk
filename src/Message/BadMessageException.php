<?php

namespace ITKFM\Geras\SDK\Message;

use Exception;
use Throwable;

class BadMessageException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}