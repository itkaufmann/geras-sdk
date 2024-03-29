<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

class NotFoundException extends ApiException
{
    public function __construct(?string $apiResponse = null)
    {
        parent::__construct('Not found', $apiResponse, 404);
    }
}
