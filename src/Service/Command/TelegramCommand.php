<?php

namespace App\Service\Command;

use App\Repository\BroadcasterRepository;
use App\Repository\SubscriptionRepository;
use App\Service\Helper\TelegramHelper;
use App\Util\Interface\CommandInterface;
use App\Util\Trait\AvailableTelegramCommand;
use Doctrine\ORM\EntityManagerInterface;

class TelegramCommand implements CommandInterface
{
    use AvailableTelegramCommand;
    protected mixed $data;
    public function __construct(
        protected ?TelegramHelper $helper
    )
    {
    }

    public function setData(mixed $data) : void
    {
        $this->helper->setData($data);
        $this->data = $data;
    }
    public function verifySignature(mixed $requestTelegramData): bool
    {
        $requestTelegramText = $this->helper->getMessageFromRequest($requestTelegramData);
        $requestTelegramDataArray = explode(' ', $requestTelegramText);
        $requestCommand = $requestTelegramDataArray[0];

        if(array_key_exists($requestCommand, $this->command))
        {
            $this->doSignatureAction(command: $this->command[$requestCommand]);
        }
        return true;
    }

    public function doSignatureAction(string $command) : void
    {
        $this->helper->$command();
    }

    public function getLogs(): array
    {
        return $this->helper->log;
    }
}