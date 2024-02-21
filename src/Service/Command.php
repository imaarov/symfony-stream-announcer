<?php

namespace App\Service;

use App\Util\Interface\CommandInterface;

class Command
{
    private CommandInterface $command;
    public function __construct()
    {    }

    public function command(CommandInterface $command) : self
    {
        $this->command = $command;
        return $this;
    }
    public function run(mixed $data): array
    {
        $this->command->setData($data);
        $this->command->verifySignature($data);
        return $this->command->getLogs();
    }
}