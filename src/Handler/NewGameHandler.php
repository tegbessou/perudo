<?php

namespace App\Handler;

use App\Entity\Game;
use App\Entity\Player;

class NewGameHandler
{
    private NewPlayerHandler $newPlayerHandler;

    public function __construct(NewPlayerHandler $newPlayerHandler)
    {
        $this->newPlayerHandler = $newPlayerHandler;
    }

    public function setup(Game $game, string $creatorPseudo, string $creatorColor): void
    {
        $player = $this->newPlayerHandler->createCreator(
            $creatorPseudo,
            $creatorColor
        );
        $this->assignPlayers($game, $player);
    }

    private function assignPlayers(Game $game, Player $player): void
    {
        $game->addPlayer($player);
        $this->assignCreator($game, $player);
        $this->assignBotPlayers($game);
    }

    private function assignCreator(Game $game, Player $player): void
    {
        $game->setCreator($player);
    }

    private function assignBotPlayers(Game $game): void
    {
        $this->newPlayerHandler->createBotPlayers($game);
    }
}
