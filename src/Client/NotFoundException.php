<?php

namespace ITKFM\Geras\SDK\Client;

class NotFoundException extends ApiException
{
    public function __construct(?string $apiResponse = null)
    {
        parent::__construct($apiResponse, 'Not found', 404);
    }
}
