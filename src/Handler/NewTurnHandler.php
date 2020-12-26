<?php

namespace App\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Manager\BetManager;
use App\Util\LaunchDiceUtil;

class NewTurnHandler
{
    private BetManager $betManager;
    private ChangeTurnPlayerHandler $changeTurnPlayerHandler;

    public function __construct(BetManager $betManager, ChangeTurnPlayerHandler $changeTurnPlayerHandler)
    {
        $this->betManager = $betManager;
        $this->changeTurnPlayerHandler = $changeTurnPlayerHandler;
    }

    public function newTurn(Game $game, ?Player $looser): void
    {
        $this->clearBets($game);
        $this->launchDices($game);
        $this->changeTurnPlayerHandler->changeTurnPlayer($game, $this->getIndexOfLooser($game, $looser));
    }

    private function clearBets(Game $game): void
    {
        $this->betManager->deleteAllBetForGame($game);
    }

    private function launchDices(Game $game): void
    {
        foreach ($game->getPlayers() as $player) {
            $player->setDices(LaunchDiceUtil::launchDices($player->getNumberOfDices()));
        }
    }

    private function getIndexOfLooser(Game $game, ?Player $looser): int
    {
        return $game->getPlayers()->indexOf($looser);
    }
}
