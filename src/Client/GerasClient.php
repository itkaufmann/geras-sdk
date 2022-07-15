<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use ITKFM\Geras\SDK\Client\ApiClientInterface as ApiClient;
use ITKFM\Geras\SDK\Entity\SessionTicket;
use ITKFM\Geras\SDK\Entity\User;
use ITKFM\Geras\SDK\Message\MessagePacker;

class GerasClient
{
    private ApiClient $client;
    private MessagePacker $messagePacker;

    public function __construct(ApiClient $client, MessagePacker $messagePacker)
    {
        $this->client = $client;
        $this->messagePacker = $messagePacker;
    }

    private function getUnpackedAs(string $uri, string $class)
    {
        $data = $this->client->get($uri, $this->messagePacker->pack(null));
        return $this->messagePacker->unpackAs($data, $class);
    }

    private function getUnpackedAsArrayOf(string $uri, string $class): array
    {
        $data = $this->client->get($uri, $this->messagePacker->pack(null));
        return $this->messagePacker->unpackAsArrayOf($data, $class);
    }

    private function getUnpackedAsArrayOfStrings(string $uri): array
    {
        $data = $this->client->get($uri, $this->messagePacker->pack(null));
        $unpacked = $this->messagePacker->unpack($data);

        if (!is_array($unpacked)) {
            throw new BadResponseException('Response data is not an array');
        }

        foreach ($unpacked as $idx => $string) {
            if (!is_string($string)) {
                throw new BadResponseException('Response array contains unexpected non-string element');
            }
        }

        return $unpacked;
    }

    private function postPackedUnpackAs(string $uri, $rqData, string $rsClass)
    {
        $rsData = $this->client->post($uri, $this->messagePacker->pack($rqData));
        return $this->messagePacker->unpackAs($rsData, $rsClass);
    }

    // ----

    /** @return User[] */
    public function getAllUsers(): array
    {
        return $this->getUnpackedAsArrayOf('users', User::class);
    }

    public function getUser(int $id): User
    {
        return $this->getUnpackedAs('users/' . $id, User::class);
    }

    /** @return string[] */
    public function getGroups(): array
    {
        return $this->getUnpackedAsArrayOfStrings('groups');
    }

    /** @return User[] */
    public function getUsersByGroup(string $group): array
    {
        return $this->getUnpackedAsArrayOf('groups/' . urlencode($group) . '/users', User::class);
    }

    /** @return string[] */
    public function getGroupsOfUser(int $userID): array
    {
        return $this->getUnpackedAsArrayOfStrings('users/' . $userID . '/groups');
    }

    public function issueTicket(): SessionTicket
    {
        return $this->postPackedUnpackAs('tickets', null, SessionTicket::class);
    }

    /**
     * @throws UnauthorizedSessionException
     */
    public function sessionGetUser(int $sessionID): User
    {
        try {
            return $this->getUnpackedAs('sessions/' . $sessionID . '/user', User::class);
        } catch (NotFoundException $ex) {
            throw new UnauthorizedSessionException($sessionID);
        }
    }
}
