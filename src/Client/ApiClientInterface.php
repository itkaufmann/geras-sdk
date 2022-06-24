<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

interface ApiClientInterface
{
    /**
     * @throws NotFoundException
     * @throws ApiException
     */
    function get(string $uri): string;

    /**
     * @throws ApiException
     */
    function post(string $uri, string $data): string;
}
