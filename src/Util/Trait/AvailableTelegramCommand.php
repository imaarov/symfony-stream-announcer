<?php
namespace App\Util\Trait;

trait AvailableTelegramCommand
{
    private array $command = [
        '/register_gp'  =>  'telegram_register_group',
        '/verify_gp'    =>  'telegram_verify_group'
    ];

    private array $supported_subscription_type = [
        'twitch'
    ];
}