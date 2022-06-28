<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Entity;

class User
{
    public int $id;

    public string $username;
    /** @var string[] */
    public array $groups;

    public string $email;

    public ?string $firstname;
    public ?string $lastname;
}
