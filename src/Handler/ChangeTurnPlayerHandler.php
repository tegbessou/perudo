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

    public function changeTurnPlayer(Game $game, int $indexNewCurrentPlayer = null): void
    {
        $indexOldCurrentPlayer = $this->getIndexOldCurrentPlayer($game);

        if ($indexNewCurrentPlayer === null) {
            $indexNewCurrentPlayer = $this->getIndexNewCurrentPlayer($game);
        }

        $this->removeMyTurnToOldPlayer($game, $indexOldCurrentPlayer);
        $this->addMyTurnToNewPlayer($game, $indexNewCurrentPlayer);

        $this->gameManager->save();
    }

    private function getIndexOldCurrentPlayer(Game $game): int
    {
        $indexNewPlayerWhoPlay = 0;

        foreach ($game->getPlayers() as $index => $player) {
            if ($player->isMyTurn()) {
                $indexNewPlayerWhoPlay = $index;
                break;
            }
        }

        return $indexNewPlayerWhoPlay;
    }

    private function getIndexNewCurrentPlayer(Game $game): int
    {
        $indexNewCurrentPlayer = $this->getIndexOldCurrentPlayer($game) + 1;

        return $this->checkIfNextPlayerExist($game->getPlayers(), $indexNewCurrentPlayer) ? $indexNewCurrentPlayer : 0;
    }

    private function checkIfNextPlayerExist(Collection $players, int $indexNewCurrentPlayer): bool
    {
        return $players->containsKey($indexNewCurrentPlayer);
    }

    private function addMyTurnToNewPlayer(Game $game, int $indexNewCurrentPlayer): void
    {
        if (!$this->checkIfNextPlayerExist($game->getPlayers(), $indexNewCurrentPlayer)) {
            throw new \LogicException('Next player specify doesn\'t exist');
        }

        $game->getPlayers()->get($indexNewCurrentPlayer)->setMyTurn(true);
    }

    private function removeMyTurnToOldPlayer(Game $game, int $indexOldCurrentPlayer): void
    {
        $game->getPlayers()->get($indexOldCurrentPlayer)->setMyTurn(false);
    }
}
