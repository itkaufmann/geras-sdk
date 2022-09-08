<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use JsonException;
use JsonMapper;
use JsonMapper_Exception;

/**
 * Utility to pack messages for transport or unpack received ones.
 */
class MessagePacker
{
    private JsonMapper $jsonMapper;

    public function __construct(JsonMapper $jsonMapper)
    {
        $this->jsonMapper = $jsonMapper;
    }

    /**
     * @throws JsonException
     */
    public function pack($raw): string
    {
        return json_encode($raw, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws BadResponseException
     */
    public function unpack(string $json)
    {
        try {
            return json_decode($json, false, 4, JSON_THROW_ON_ERROR);
        } catch (JsonException $ex) {
            throw new BadResponseException($ex->getMessage(), $json);
        }
    }

    /**
     * @throws BadResponseException
     */
    public function unpackAs(string $json, string $class): object
    {
        $data = $this->unpack($json);

        if (!is_object($data)) {
            throw new BadResponseException('Object expected, but data is ' . gettype($data));
        }

        try {
            return $this->jsonMapper->map($data, new $class());
        } catch (JsonMapper_Exception $ex) {
            throw new BadResponseException($ex->getMessage(), $json);
        }
    }

    /**
     * @throws BadResponseException
     */
    public function unpackAsArrayOf(string $json, string $class): array
    {
        $data = $this->unpack($json);

        if (!is_array($data)) {
            throw new BadResponseException('Array expected, but data is ' . gettype($data));
        }

        try {
            return $this->jsonMapper->mapArray($data, [], $class);
        } catch (JsonMapper_Exception $ex) {
            throw new BadResponseException($ex->getMessage(), $json);
        }
    }

    /**
     * @return string[]
     * @throws BadResponseException
     */
    public function unpackAsArrayOfStrings(string $json): array
    {
        $data = $this->unpack($json);

        if (!is_array($data)) {
            throw new BadResponseException('Array expected, but data is ' . gettype($data));
        }

        foreach ($data as $idx => $string) {
            if (!is_string($string)) {
                throw new BadResponseException('Response array contains unexpected non-string element @' . $idx);
            }
        }

        return $data;
    }
}
