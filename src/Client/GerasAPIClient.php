<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use Exception;
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

    private function getUnpacked(string $uri)
    {
        $data = $this->client->get($uri);
        return $this->messagePacker->unpackData($data);
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

    private function postPacked(string $uri, $rqData)
    {
        $rsData = $this->client->post($uri, $this->messagePacker->packData($rqData));
        return $this->messagePacker->unpackData($rsData);
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

    public function issueTicket(): Group
    {
        return $this->postPackedUnpackAs('ticket', null, Ticket::class);
    }

    public function sessionGetUser(string $ticketID): User
    {
        return $this->postPackedUnpackAs('ticket/' . $ticketID . '/user', null, User::class);
    }

    public function sessionSetVar(string $ticketID, string $key, string $value): void
    {
        $result = $this->postPacked('ticket/' . urlencode($ticketID) . '/var/' . urlencode($key), $value);

        if (!is_bool($result)) {
            throw new Exception('Unexpected response from server: ' . json_encode($result));
        }

        if ($result !== true) {
            throw new Exception('Failed to set session variable.');
        }
    }

    public function sessionGetVar(string $ticketID, string $key): string
    {
        $result = $this->getUnpacked('ticket/' . urlencode($ticketID) . '/var/' . urlencode($key));

        if (!is_string($result)) {
            throw new Exception('Bad key/value pair');
        }

        return $result;
    }
}
