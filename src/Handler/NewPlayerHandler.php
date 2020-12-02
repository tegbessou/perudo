<?php

namespace App\Handler;

use App\Factory\PlayerFactory;
use App\Model\GameModel;
use App\Model\PlayerModel;

class NewPlayerHandler
{
    private PlayerFactory $playerFactory;
    private array $remainingColor = PlayerModel::DICE_COLOR;

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
                $this->isBot($players),
                $this->selectColor($gameModel, $players)
            );
            ++$index;
        }

        return $players;
    }

    private function selectPseudo(GameModel $gameModel, array $players): string
    {
        return empty($players) ? $gameModel->getCreator() : PlayerFactory::BOT_PSEUDO.count($players);
    }

    private function selectColor(GameModel $gameModel, array $players): string
    {
        $this->updateRemainingColors($players);

        return empty($players) ? $gameModel->getCreatorColor() : $this->getRandomColorInRemaining();
    }

    private function updateRemainingColors(array $players): void
    {
        /** @var PlayerModel $player */
        foreach ($players as $player) {
            $key = array_search($player->getDiceColor(), $this->remainingColor);
            if ($key === false) {
                continue;
            }
            unset($this->remainingColor[$key]);
        }

        $this->remainingColor = array_values($this->remainingColor);
    }

    private function getRandomColorInRemaining(): string
    {
        return $this->remainingColor[random_int((int) array_key_first($this->remainingColor), (int) array_key_last($this->remainingColor))];
    }

    private function isBot(array $players): bool
    {
        return !empty($players);
    }
}
