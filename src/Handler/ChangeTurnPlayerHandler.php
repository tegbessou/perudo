<?php

namespace App\Handler;

use App\Entity\Game;
use App\Manager\GameManager;
use Doctrine\Common\Collections\Collection;

class ChangeTurnPlayerHandler
{
    private GameManager $gameManager;

    public function __construct(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;
    }

    public function changeTurnPlayer(Game $game): void
    {
        $indexOfNextPlayer = $this->chooseWhichIndexOfPlayerWillPlay($game->getPlayers());
        $this->applyChangeTurnPlayer($game, $indexOfNextPlayer);
    }

    private function chooseWhichIndexOfPlayerWillPlay(Collection $players): int
    {
        $indexNewPlayerWhoPlay = 0;

        foreach ($players as $index => $player) {
            if ($player->isMyTurn()) {
                $indexNewPlayerWhoPlay = $index;
                break;
            }
        }

        return $this->checkIfNextPlayerExist($players, $indexNewPlayerWhoPlay) ? $indexNewPlayerWhoPlay : 0;
    }

    private function checkIfNextPlayerExist(Collection $players, int $indexOfNextPlayer): bool
    {
        return $players->containsKey($indexOfNextPlayer);
    }

    private function applyChangeTurnPlayer(Game $game, int $indexOfNextPlayer): void
    {
        ($game->getPlayers()->get($indexOfNextPlayer))
            ->setMyTurn(false);
        ($game->getPlayers()->get($indexOfNextPlayer + 1))
            ->setMyTurn(true);

        $this->gameManager->save();
    }
}
