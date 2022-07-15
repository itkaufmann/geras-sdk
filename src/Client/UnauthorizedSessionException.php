<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Client;

class UnauthorizedSessionException extends ApiException
{
    private int $sessionID;

    public function __construct(int $sessionID)
    {
        $this->sessionID = $sessionID;
        parent::__construct('No active session for this ticket yet. User has to login first.');
    }

    public function getSessionID(): int
    {
        return $this->sessionID;
    }
}
