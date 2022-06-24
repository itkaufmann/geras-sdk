<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use GuzzleHttp\Client;

class HttpApiClient implements ApiClientInterface
{
    private Client $http;

    public function __construct(string $gerasApiBaseURL, array $guzzleHttpConfig = [])
    {
        $guzzleHttpConfig['base_uri'] = $gerasApiBaseURL;
        $guzzleHttpConfig['http_errors'] = false;
        $this->http = new Client($guzzleHttpConfig);
    }

    public function get(string $uri): string
    {
        $response = $this->http->get($uri);

        if ($response->getStatusCode() !== 200) {
            throw ($response->getStatusCode() === 404)
                ? new NotFoundException()
                : new ApiException(null, $response->getStatusCode());
        }

        return (string)$response->getBody();
    }

    public function post(string $uri, $data): string
    {
        $response = $this->http->post($uri, ['body' => $data, JSON_THROW_ON_ERROR]);

        if (($response->getStatusCode() > 204) || ($response->getStatusCode() < 200)) {
            throw new ApiException(null, $response->getStatusCode());
        }

        return (string)$response->getBody();
    }
}
