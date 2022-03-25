<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

interface ApiClientInterface
{
    function get(string $uri): string;

    function post(string $uri, string $data): string;
}
