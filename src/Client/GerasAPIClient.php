<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use ITKFM\Geras\SDK\Client\ApiClientInterface as ApiClient;
use ITKFM\Geras\SDK\Entity\Group;
use ITKFM\Geras\SDK\Entity\Ticket;
use ITKFM\Geras\SDK\Entity\User;
use ITKFM\Geras\SDK\Message\MessagePacker;

class GerasAPIClient
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
        $data = $this->client->get($uri);
        return $this->messagePacker->unpackDataAs($data, $class);
    }

    private function getUnpackedAsArrayOf(string $uri, string $class): array
    {
        $data = $this->client->get($uri);
        return $this->messagePacker->unpackDataAsArrayOf($data, $class);
    }

    private function postPackedUnpackAs(string $uri, $rqData, string $rsClass)
    {
        $rsData = $this->client->post($uri, $this->messagePacker->packData($rqData));
        return $this->messagePacker->unpackDataAs($rsData, $rsClass);
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

    /** @return Group[] */
    public function getGroups(): array
    {
        return $this->getUnpackedAsArrayOf('groups', Group::class);
    }

    public function getGroup(int $id): Group
    {
        return $this->getUnpackedAs('groups/' . $id, Group::class);
    }

    /** @return User[] */
    public function getUsersByGroup(int $groupID): array
    {
        return $this->getUnpackedAsArrayOf('groups/' . $groupID . '/users', User::class);
    }

    /** @return Group[] */
    public function getGroupsOfUser(int $userID): array
    {
        return $this->getUnpackedAsArrayOf('users/' . $userID . 'groups', Group::class);
    }

    public function isUserInGroup(int $userID, int $groupID): bool
    {
        // TODO
    }

    public function issueTicket(int $id): Group
    {
        return $this->postPackedUnpackAs('ticket', null, Ticket::class);
    }

    public function sessionSetVar(string $sessionID, string $id, $value): void
    {
        // TODO
    }

    public function sessionGetVar(string $sessionID, string $id)
    {
        // TODO
    }
}
