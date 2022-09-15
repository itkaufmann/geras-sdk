<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use Exception;

class ApiException extends Exception
{
    private ?string $apiResponse;

    public function __construct(string $message, ?string $apiResponse = null, int $code = 0)
    {
        $this->apiResponse = $apiResponse;
        parent::__construct($message, $code);
    }

    public function getApiResponse(): ?string
    {
        return $this->apiResponse;
    }
}
