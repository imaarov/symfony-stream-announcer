<?php

namespace App\Util\Interface;

interface DispatcherInterface
{
    public function verifyMessage();

    public function is_subscribe();

    public function subscribe();

    public function saveData();
}