<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Bet;
use App\Handler\ChangeTurnPlayerHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostBetChangePlayerWhoPlaySubscriber implements EventSubscriberInterface
{
    private ChangeTurnPlayerHandler $changeTurnPlayerHandler;

    public function __construct(ChangeTurnPlayerHandler $changeTurnPlayerHandler)
    {
        $this->changeTurnPlayerHandler = $changeTurnPlayerHandler;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['switchPlayerWhoPlay', EventPriorities::POST_WRITE],
        ];
    }

    public function switchPlayerWhoPlay(ViewEvent $event): void
    {
        $lastBet = $event->getControllerResult();

        if (!$lastBet instanceof Bet || Request::METHOD_POST !== $event->getRequest()->getMethod()) {
            return;
        }

        $this->changeTurnPlayerHandler->changeTurnPlayer($lastBet->getGame());
    }
}
