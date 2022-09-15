<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use Exception;

final class ApiKey
{
    private string $gerasApiBaseUrl;
    private int $appID;
    private string $secret;

    /**
     * @throws Exception
     */
    public function __construct(string $gerasApiBaseUrl, int $appID, string $secretEncoded)
    {
        $this->gerasApiBaseUrl = $gerasApiBaseUrl;
        $this->appID = $appID;

        $secret = base64_decode($secretEncoded);
        if ($secret === false) {
            throw new Exception('Bad API key format: not in base64_deocde\'able');
        }
        $this->secret = $secret;
    }

    public function getGerasApiBaseUrl(): string
    {
        return $this->gerasApiBaseUrl;
    }

    public function getAppID(): int
    {
        return $this->appID;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
