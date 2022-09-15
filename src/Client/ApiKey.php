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
    public function __construct(string $gerasApiBaseUrl, int $appID, string $secretBase64Encoded)
    {
        $this->gerasApiBaseUrl = $gerasApiBaseUrl;
        $this->appID = $appID;
        $this->secret = sodium_base642bin($secretBase64Encoded, SODIUM_BASE64_VARIANT_ORIGINAL);
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
