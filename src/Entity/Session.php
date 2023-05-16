<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Entity;

class Session
{
    public int $sessionID;
    public ?string $loginURL = null;
    public ?User $user = null;
}
