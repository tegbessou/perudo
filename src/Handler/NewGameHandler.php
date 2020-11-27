<?php

namespace App\Handler;

use App\Model\GameModel;

class NewGameHandler
{
    private NewPlayerHandler $newPlayerHandler;

    public function __construct(NewPlayerHandler $newPlayerHandler)
    {
        $this->newPlayerHandler = $newPlayerHandler;
    }

    public function assignPlayer(GameModel $gameModel): GameModel
    {
        return $gameModel->setPlayers($this->newPlayerHandler->createPlayers($gameModel));
    }
}
