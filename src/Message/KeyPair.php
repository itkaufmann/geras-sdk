<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

final class KeyPair
{
    private string $publicKey;
    private string $secretKey;

    public function __construct(string $publicKey, string $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}


