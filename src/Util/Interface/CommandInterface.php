<?php

namespace App\Util\Interface;

interface CommandInterface
{
    public function verifySignature(mixed $requestTelegramData) : bool;

    public function doSignatureAction(string $command) : void;

    public function setData(mixed $data) : void;

    public function getLogs() : array;
}