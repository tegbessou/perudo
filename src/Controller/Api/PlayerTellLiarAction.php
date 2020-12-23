<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Handler\LiarHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerTellLiarAction
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(Player $player, LiarHandler $liarHandler): JsonResponse
    {
        try {
            $looser = $liarHandler->liar($player);
        } catch (\LogicException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $this->response($looser, $player);
    }

    private function response(Player $looser, Player $player): JsonResponse
    {
        return new JsonResponse(
            [
                'looser' => $looser->getPseudo(),
                'looserIsLiar' => $player === $looser,
                'players' => $this->serializer->serialize($player->getGame()->getPlayers(), 'json'),
            ],
            Response::HTTP_OK
        );
    }
}
