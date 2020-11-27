<?php

namespace App\Handler;

use App\Factory\PlayerFactory;
use App\Model\GameModel;

class NewPlayerHandler
{
    private PlayerFactory $playerFactory;

    public function __construct(PlayerFactory $playerFactory)
    {
        $this->playerFactory = $playerFactory;
    }

    public function createPlayers(GameModel $gameModel): array
    {
        $players = [];
        $index = 0;
        while ($index < $gameModel->getNumberOfPlayers()) {
            $players[] = $this->playerFactory->initialize(
                $this->selectPseudo($gameModel, $players),
                $this->isBot($players)
            );
            ++$index;
        }

        return $players;
    }

    private function selectPseudo(GameModel $gameModel, array $players): string
    {
        return empty($players) ? $gameModel->getCreator() : PlayerFactory::BOT_PSEUDO.count($players);
    }

    private function isBot(array $players): bool
    {
        return !empty($players);
    }
}
