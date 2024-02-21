<?php

namespace App\Service\Command;

use App\Util\Interface\CommandInterface;

class DiscordCommand implements CommandInterface
{

    public function verifySignature(mixed $requestTelegramData): bool
    {
        // TODO: Implement verifySignature() method.
    }

    public function doSignatureAction(string $command) : void
    {
        // TODO: Implement doSignatureAction() method.
    }

    public function setData(mixed $data): void
    {
        // TODO: Implement setData() method.
    }

    public function getLogs(): array
    {
        // TODO: Implement getLogs() method.
        return [];
    }
}