<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

use ITKFM\Geras\SDK\Client\ApiClientInterface as ApiClient;
use ITKFM\Geras\SDK\Entity\Session;
use ITKFM\Geras\SDK\Entity\User;
use JsonException;

/**
 * G.E.R.A.S. client
 */
class GerasClient
{
    private ApiClient $client;
    private MessagePacker $messagePacker;

    public function __construct(ApiClient $client, MessagePacker $messagePacker)
    {
        $this->client = $client;
        $this->messagePacker = $messagePacker;
    }

    /**
     * @return mixed
     * @throws ApiException
     */
    private function getUnpackedAs(string $uri, string $class, array $queryParameters = []): object
    {
        $data = $this->client->get($uri, $queryParameters);
        return $this->messagePacker->unpackAs($data, $class);
    }

    /**
     * @throws ApiException
     */
    private function getUnpackedAsArrayOf(string $uri, string $class): array
    {
        $data = $this->client->get($uri);
        return $this->messagePacker->unpackAsArrayOf($data, $class);
    }

    /**
     * @return string[]
     * @throws ApiException
     */
    private function getUnpackedAsArrayOfStrings(string $uri): array
    {
        $data = $this->client->get($uri);
        return $this->messagePacker->unpackAsArrayOfStrings($data);
    }

    /**
     * @return mixed
     * @throws ApiException
     * @throws JsonException
     */
    private function postUnpackedAs(string $uri, $requestData, string $responseClass): object
    {
        $rsData = $this->client->post($uri, $this->messagePacker->pack($requestData));
        return $this->messagePacker->unpackAs($rsData, $responseClass);
    }

    // ----

    /**
     * @return User[]
     * @throws ApiException
     */
    public function getAllUsers(): array
    {
        return $this->getUnpackedAsArrayOf('users', User::class);
    }

    /**
     * @throws ApiException
     */
    public function getUser(int $id): User
    {
        return $this->getUnpackedAs('users/' . $id, User::class);
    }

    /**
     * @return string[]
     * @throws ApiException
     */
    public function getGroups(): array
    {
        return $this->getUnpackedAsArrayOfStrings('groups');
    }

    /**
     * @return User[]
     * @throws ApiException
     */
    public function getUsersByGroup(string $group): array
    {
        return $this->getUnpackedAsArrayOf('groups/' . urlencode($group) . '/users', User::class);
    }

    /**
     * @return string[]
     * @throws ApiException
     */
    public function getGroupsOfUser(int $userID): array
    {
        return $this->getUnpackedAsArrayOfStrings('users/' . $userID . '/groups');
    }

    // -- session

    /**
     * @throws ApiException
     * @throws JsonException
     */
    public function issueSessionTicket(): Session
    {
        return $this->postUnpackedAs('sessions?two-way-confirm=false', null, Session::class);
    }

    public function sessionSubmitTwoWayToken(int $sessionID, string $token): Session
    {
        return $this->postUnpackedAs(
            'sessions/' . $sessionID . '/two-way-confirm',
            $token,
            Session::class
        );
    }

    /**
     * @throws ApiException
     */
    public function sessionGetInfo(int $sessionID): Session
    {
        return $this->getUnpackedAs('sessions/' . $sessionID, Session::class);
    }

    /**
     * @throws ApiException
     */
    public function sessionDestroy(int $sessionID): void
    {
        $this->client->delete('sessions/' . $sessionID);
    }

    // -- token auth

    /**
     * @throws ApiException
     * @throws BadResponseException
     */
    public function tokenCreateUrlForUser(): string
    {
        $data = $this->client->get('tokens/create-url');
        return $this->messagePacker->unpack($data);
    }

    /**
     * @return User|null The User authenticated through the provided token or NULL if the token is not valid
     * @throws ApiException
     */
    public function tokenValidate(string $tokenName, string $tokenSecret): ?User
    {
        try {
            return $this->getUnpackedAs(
                'tokens/' . rawurlencode($tokenName) . '/user',
                User::class,
                [
                    'secret' => $tokenSecret,
                ]
            );
        } catch (NotFoundException $ex) {
            if ($ex->getApiResponse() === 'Invalid App Token') {
                return null;
            }

            throw $ex;
        }
    }
}
