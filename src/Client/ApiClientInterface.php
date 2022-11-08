<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

interface ApiClientInterface
{
    /**
     * @throws ApiException
     */
    function get(string $uri, array $queryParameters = []): string;

    /**
     * @throws ApiException
     */
    function post(string $uri, ?string $data): ?string;

    /**
     * @throws ApiException
     */
    function delete(string $uri): void;
}
