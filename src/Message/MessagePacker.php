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
    private int $verifyMaxTimeOffset;

    public function __construct(SignatureHelper $signatureHelper, JsonMapper $jsonMapper, int $verifyMaxTimeOffset = 7)
    {
        $this->signatureHelper = $signatureHelper;
        $this->jsonMapper = $jsonMapper;
        $this->verifyMaxTimeOffset = $verifyMaxTimeOffset;
    }

    public function pack(Message $message): string
    {
        $json = json_encode($message, JSON_THROW_ON_ERROR);
        return $this->signatureHelper->sign($json);
    }

    public function unpack(string $packed): Message
    {
        $json = $this->signatureHelper->verify($packed);
        $o = json_decode($json, false, 4, JSON_THROW_ON_ERROR);
        return $this->jsonMapper->map($o, new Message());
    }

    public function packData($data): string
    {
        $message = new Message();
        $message->timestamp = time();
        $message->data = $data;

        return $this->pack($message);
    }

    public function unpackData(string $packed)
    {
        $now = time();

        $o = $this->unpack($packed);

        // verify timestamp
        $delta = $now - $o->timestamp;
        if ($delta > $this->verifyMaxTimeOffset)
            throw new MessageExpiredException($o->timestamp, $now);
        elseif ($delta < (-1 * $this->verifyMaxTimeOffset))
            throw new MessageNotValidYetException($o->timestamp, $now);

        return $o->data;
    }

    /** @return mixed */
    public function unpackDataAs(string $packed, string $class): object
    {
        $data = $this->unpackData($packed);
        return $this->jsonMapper->map($data, new $class());
    }

    public function unpackDataAsArrayOf(string $packed, string $class): array
    {
        $data = $this->unpackData($packed);
        return $this->jsonMapper->mapArray($data, [], $class);
    }
}
