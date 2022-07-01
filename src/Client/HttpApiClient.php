<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use GuzzleHttp\Client;

class HttpApiClient implements ApiClientInterface
{
    private Client $http;
    private int $applicationID;

    public function __construct(string $gerasApiBaseURL, int $applicationID, array $guzzleHttpConfig = [])
    {
        $guzzleHttpConfig['base_uri'] = $gerasApiBaseURL . '/apps/' . $applicationID . '/';
        $guzzleHttpConfig['http_errors'] = false;
        $this->http = new Client($guzzleHttpConfig);
        $this->applicationID = $applicationID;
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
        $response = $this->http->post($uri, [
            'body' => $data,
        ]);

        if (($response->getStatusCode() > 204) || ($response->getStatusCode() < 200)) {
            throw new ApiException(null, $response->getStatusCode());
        }

        return (string)$response->getBody();
    }
}
