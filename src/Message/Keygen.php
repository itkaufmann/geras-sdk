<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

class Keygen
{
    static function generateKeyPair(): KeyPair
    {
        $keyPair = sodium_crypto_sign_keypair();
        $publicKey = sodium_crypto_sign_publickey($keyPair);
        $secretKey = sodium_crypto_sign_secretkey($keyPair);
        return new KeyPair($publicKey, $secretKey);
    }
}
