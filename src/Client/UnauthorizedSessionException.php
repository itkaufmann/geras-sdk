<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

class UnauthorizedSessionException extends ApiException
{
    private string $sessionID;

    public function __construct(string $sessionID)
    {
        $this->sessionID = $sessionID;
        parent::__construct('No active session for this ticket yet. User has to login first.');
    }

    public function getSessionID(): string
    {
        return $this->sessionID;
    }
}
