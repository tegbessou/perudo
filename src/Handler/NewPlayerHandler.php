<?php

namespace App\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Factory\PlayerFactory;
use Doctrine\Common\Collections\Collection;

class NewPlayerHandler
{
    private PlayerFactory $playerFactory;
    private array $remainingColor = Player::DICE_COLOR;

    public function __construct(PlayerFactory $playerFactory)
    {
        $this->playerFactory = $playerFactory;
    }

    public function createBotPlayers(Game $game): Game
    {
        $index = 0;
        $playerWhichStart = $this->whoStarting((int) $game->getNumberOfPlayers());
        while ($index < $game->getNumberOfPlayers() - 1) {
            $game->addPlayer($this->playerFactory->initialize(
                $this->createBotPseudo($game->getPlayers()),
                true,
                $this->selectColor($game, $game->getPlayers()),
                $index === $playerWhichStart ?? false
            ));
        }

        return $game;
    }

    public function createCreator(string $pseudo, string $color): Player
    {
        return $this->playerFactory->initialize(
            $pseudo,
            false,
            $color
        );
    }

    private function createBotPseudo(Collection $players): string
    {
        return PlayerFactory::BOT_PSEUDO.$players->count();
    }

    private function selectColor(Game $game, Collection $players): string
    {
        $this->updateRemainingColors($players);

        return $this->getRandomColorInRemaining();
    }

    private function updateRemainingColors(Collection $players): void
    {
        /** @var Player $player */
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

    private function whoStarting(int $numberOfPlayer): int
    {
        return random_int(0, $numberOfPlayer - 1);
    }
}
