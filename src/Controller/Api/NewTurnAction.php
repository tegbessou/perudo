<?php

namespace App\Controller\Api;

use App\Entity\Game;
use App\Entity\Player;
use App\Handler\NewTurnHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class NewTurnAction
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, Game $game, NewTurnHandler $newTurnHandler, EntityManagerInterface $entityManager): Response
    {
        $body = json_decode((string) $request->getContent(), true);
        try {
            if (!isset($body['looserId'])) {
                throw new \LogicException('Le looser Id is mandatory!');
            }

            $looser = $entityManager->getRepository(Player::class)->find($body['looserId']);
            $newTurnHandler->newTurn($game, $looser);
        } catch (\LogicException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
