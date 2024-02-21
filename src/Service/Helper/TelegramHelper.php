<?php

namespace App\Service\Helper;

use App\Entity\Broadcaster;
use App\Entity\Event;
use App\Entity\Receiver;
use App\Entity\Subscription;
use App\Repository\BroadcasterRepository;
use App\Repository\SubscriptionRepository;
use App\Util\Trait\AvailableTelegramCommand;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\String\ByteString;

class TelegramHelper
{
    use AvailableTelegramCommand;

    protected ?array $data;
    public array $log = [];
    public function __construct(
        protected EntityManagerInterface $entity,
        protected BroadcasterRepository $broadcasterRepository,
        protected SubscriptionRepository $subscriptionRepository
    )
    {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function getMessageFromRequest(array $requestTelegram) : string
    {
        return $requestTelegram['message']['text'];
    }

    protected function getChatIdFromRequest(array $requestTelegram): string
    {
        return $requestTelegram['message']['chat']['id'];
    }

    // Handling Command Execution:

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function telegram_register_group() : bool
    {
        $entity = $this->entity;
        [$name, $broadcastPlatform, $broadCastId] = $this->prepare_telegram_group_registering_data();
        if($this->broadcasterRepository->findOneBy(['name' => $name])) {
            $this->log['error'] = [
                'user exists !'
            ];
            return false;
        }
        # Example Command: /register_gp $name $broadCasterPlatform $broadCastId
        #TODO Create the broadcaster
        if (!in_array($broadcastPlatform, $this->supported_subscription_type, true)) {
            return false;
        }
        $broadcaster = new Broadcaster();
        $broadcaster->setName($name);
        $broadcaster->setToken(ByteString::fromRandom(32)->toString());
        $broadcaster->setIsActive(0);
        $broadcaster->setCreatedAt(new DateTimeImmutable());
        # New subscription for broadcaster
        $subscription = new Subscription();
        $subscription->setType($broadcastPlatform);
        $subscription->setIsActive(0);
        $subscription->setSubUserId($broadCastId);
        $subscription->setBroadcaster($broadcaster);

        $entity->persist($broadcaster);
        $entity->persist($subscription);
        $entity->flush();
        return true;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function telegram_verify_group() : bool
    {
        $broadcasterRepository = $this->broadcasterRepository;
        $subscriptionRepository = $this->subscriptionRepository;
        [$name, $subscription, $token, $events, $chat_id] = $this->prepare_telegram_group_verifying_data();
        $broadcaster = $broadcasterRepository->findOneBy([
            'name'          =>  $name,
            'token'         =>  $token,
            'is_active'     =>  0
        ]);
        if($broadcaster) {
            $sub = $subscriptionRepository->findOneBy([
                'type'              =>      $subscription,
                'broadcaster'    =>      $broadcaster->getId()
            ]);
            $broadcaster->setIsActive(1);
            $this->entity->persist($broadcaster);
            foreach ($events as $event) {
                $event_ = new Event();
                $event_->setType($event);
                $event_->setSubscription($sub);
                $event_->setIsActive(1);

                $receiver_ = new Receiver();
                $receiver_->setType('telegram');
                $receiver_->setChatId($chat_id);
                $receiver_->setEvent($event_);
                $this->entity->persist($event_);
                $this->entity->persist($receiver_);
            }
            $this->entity->flush();
        } else{
            $this->log['error'] = [
                'You are already active'
            ];
        }
        return (bool)$broadcaster;
    }

    #[ArrayShape(['name' => "string", 'subscription' => "string", 'subscriptionId' => "string"])]
    private function prepare_telegram_group_registering_data() : array
    {
        $arrayData = explode(' ', $this->getMessageFromRequest($this->data));
        return [
            $arrayData[1],      //name
            $arrayData[2],      //subscription
            $arrayData[3]       //subscriptionId
        ];
    }
    #[ArrayShape(['name' => "string", 'subscription' => "string", 'token' => "string", 'events' => "string[]", 'chat_id' => 'string'])]
    private function prepare_telegram_group_verifying_data() : array
    {
        $arrayData = explode(' ', $this->getMessageFromRequest($this->data));
        return [
            $arrayData[1],                              //name
            $arrayData[2],                              //subscription
            $arrayData[3],                              //token
            explode(',', $arrayData[4]),       //events
            $this->getChatIdFromRequest($this->data)    //chat_id
        ];
    }
}