<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

use Exception;

class BadSignatureException extends Exception
{
    private string $badMessage;

    public function __construct(string $badMessage)
    {
        parent::__construct('Signature verification failed.');
    }
}
