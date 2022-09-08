<?php

namespace ITKFM\Geras\SDK\Client;

class BadResponseException extends ApiException
{
    public function __construct(string $message, ?string $apiResponse = null)
    {
        parent::__construct($message, $apiResponse, -1);
    }
}
