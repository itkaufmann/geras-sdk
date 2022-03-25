<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use GuzzleHttp\Client;
use JsonMapper;

// TODO: error handling

class HttpJsonApiClient implements ApiClientInterface
{
    private Client $http;

    public function __construct(string $gerasApiBaseURL, array $guzzleHttpConfig = [])
    {
        $guzzleHttpConfig['base_uri'] = $gerasApiBaseURL;
        $this->http = new Client($guzzleHttpConfig);
    }

    public function get(string $uri): string
    {
        $response = $this->http->get($uri);
        return (string)$response->getBody();
    }

    public function post(string $uri, $data): string
    {
        $response = $this->http->post($uri, ['body' => json_encode($data, JSON_THROW_ON_ERROR)]);
        return (string)$response->getBody();
    }
}
