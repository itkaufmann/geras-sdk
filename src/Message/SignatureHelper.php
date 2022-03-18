<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

/**
 * Higher level abstraction for signing messages and verifying them
 */
final class SignatureHelper
{
    private string $ownSecretKey;
    private string $othersPublicKey;

    /**
     * @param string $ownSecretKey Your apps secret signing key
     * @param string $othersPublicKey The other parties public signature verification key
     */
    public function __construct(string $ownSecretKey, string $othersPublicKey)
    {
        $this->ownSecretKey = $ownSecretKey;
        $this->othersPublicKey = $othersPublicKey;
    }

    /**
     * @return string Signed Message (incl. signature)
     */
    function sign(string $ownMessage): string
    {
        return sodium_crypto_sign($ownMessage, $this->ownSecretKey);
    }

    /**
     * @return string Message with chopped off signature
     * @throws BadSignatureException in case of a signature verification failure
     * @throws \SodiumException
     */
    function verify(string $othersMessage): string
    {
        $r = sodium_crypto_sign_open($othersMessage, $this->othersPublicKey);

        if ($r === false)
            throw new BadSignatureException($othersMessage);

        return $r;
    }
}

