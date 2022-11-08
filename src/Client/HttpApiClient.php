<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use GuzzleHttp\ClientInterface as GuzzleClient;

/**
 * Guzzle-based ApiClient implementation for G.E.R.A.S.
 */
class HttpApiClient implements ApiClientInterface
{
    private ApiKey $apiKey;
    private GuzzleClient $http;

    public function __construct(ApiKey $gerasApiKey, GuzzleClient $http)
    {
        $this->apiKey = $gerasApiKey;
        $this->http = $http;
    }

    public function get(string $uri, array $queryParameters = []): string
    {
        $response = $this->http->get(
            $uri,
            $this->getRequestOptions([
                'query' => $queryParameters,
            ])
        );

        if ($response->getStatusCode() !== 200) {
            throw ($response->getStatusCode() === 404)
                ? new NotFoundException()
                : new ApiException(
                    'Error indicated by response status code',
                    (string)$response->getBody(),
                    $response->getStatusCode()
                );
        }

        return (string)$response->getBody();
    }

    public function post(string $uri, $data): ?string
    {
        $response = $this->http->post(
            $uri,
            $this->getRequestOptions([
                'body' => $data,
            ])
        );

        if (($response->getStatusCode() > 204) || ($response->getStatusCode() < 200)) {
            throw new ApiException(
                'Error indicated by response status code',
                (string)$response->getBody(),
                $response->getStatusCode()
            );
        }

        return ($response->getBody()->eof())
            ? null
            : (string )$response->getBody();
    }

    public function delete(string $uri): void
    {
        $response = $this->http->delete($uri, $this->getRequestOptions());

        if (($response->getStatusCode() > 204) || ($response->getStatusCode() < 200)) {
            throw new ApiException(
                'Error indicated by response status code',
                (string)$response->getBody(),
                $response->getStatusCode()
            );
        }
    }

    public function getApplicationID(): int
    {
        return $this->apiKey->getAppID();
    }

    private function getRequestOptions(array $options = []): array
    {
        return array_merge(
            [
                'auth' => [(string)$this->apiKey->getAppID(), $this->apiKey->getSecret()],
                'base_uri' => $this->apiKey->getGerasApiBaseUrl() . '/apps/' . $this->apiKey->getAppID() . '/',
                'http_errors' => false,
            ],
            $options
        );
    }
}
