<?php

namespace App\Controller;

use App\Service\Command;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{
    protected Dotenv $dotenv;
    public function __construct(
        private Command $command,
        private Command\TelegramCommand $telegramCommand
    )
    {
        $this->dotenv = new Dotenv(__DIR__ . '/../../.env');
    }

    /**
     * @throws \JsonException
     */
    #[Route('/telegram/listen', name: 'app_telegram_listen')]
    public function listen(): Response
    {
        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        $response = $this
                        ->command
                        ->command($this->telegramCommand)
                        ->run(data: $data);
        return new JsonResponse($response, isset($response['error']) ? 500 : 201);
    }
}
