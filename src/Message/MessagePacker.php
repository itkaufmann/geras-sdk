<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

use JsonMapper;

/**
 * Utility to pack messages for transport or unpack received ones.
 * Handles timestamp addition and verification.
 */
class MessagePacker
{
    private SignatureHelper $signatureHelper;
    private JsonMapper $jsonMapper;

    public function __construct(SignatureHelper $signatureHelper, JsonMapper $jsonMapper)
    {
        $this->signatureHelper = $signatureHelper;
        $this->jsonMapper = $jsonMapper;
    }

    public function pack($raw): string
    {
        $json = json_encode($raw, JSON_THROW_ON_ERROR);
        return $this->signatureHelper->sign($json);
    }

    public function unpack(string $packed)
    {
        $json = $this->signatureHelper->verify($packed);
        return json_decode($json, false, 4, JSON_THROW_ON_ERROR);
    }

    /** @return mixed */
    public function unpackAs(string $packed, string $class): object
    {
        $data = $this->unpack($packed);

        if (!is_object($data)) {
            throw new BadMessageException('Object expected, but data is ' . gettype($data));
        }

        return $this->jsonMapper->map($data, new $class());
    }

    public function unpackAsArrayOf(string $packed, string $class): array
    {
        $data = $this->unpack($packed);

        if (!is_array($data)) {
            throw new BadMessageException('Array expected, but data is ' . gettype($data));
        }

        return $this->jsonMapper->mapArray($data, [], $class);
    }
}
