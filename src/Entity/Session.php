<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Entity;

class Session
{
    public int $sessionID;
    public ?User $user;
    public ?string $loginURL;
    public int $validUntil;
}
