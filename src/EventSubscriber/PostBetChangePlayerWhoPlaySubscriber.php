<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Bet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostBetChangePlayerWhoPlaySubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

        // One method
        $indexNewPlayerWhoPlay = 0;

        foreach ($lastBet->getGame()->getPlayers() as $index => $player) {
            if ($player->isMyTurn()) {
                $indexNewPlayerWhoPlay = $index;
                break;
            }
        }

        //Other method
        if(!$lastBet->getGame()->getPlayers()->containsKey($indexNewPlayerWhoPlay+1)) {
            $indexNewPlayerWhoPlay = 0;
        };

        ($lastBet->getGame()->getPlayers()->get($indexNewPlayerWhoPlay))
            ->setMyTurn(false);
        ($lastBet->getGame()->getPlayers()->get($indexNewPlayerWhoPlay+1))
            ->setMyTurn(true);
        $this->entityManager->flush();
    }
}