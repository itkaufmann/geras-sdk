<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Entity;

class Session
{
    public int $sessionID;
    public ?User $user = null;
    public ?string $loginURL = null;
    public int $validUntil;
}
