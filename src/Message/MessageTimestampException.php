<?php

declare(strict_types=1);

namespace ITKFM\Geras\SDK\Message;

use Exception;

abstract class MessageTimestampException extends Exception
{
    private int $messageTimestamp;
    private int $verificationTimestamp;

    public function __construct(int $messageTimestamp, int $verificationTimestamp)
    {
        $this->messageTimestamp = $messageTimestamp;
        $this->verificationTimestamp = $verificationTimestamp;
        parent::__construct('Message not valid now.');
    }

    public function getMessageTimestamp(): int
    {
        return $this->messageTimestamp;
    }

    public function getVerificationTimestamp(): int
    {
        return $this->verificationTimestamp;
    }
}
