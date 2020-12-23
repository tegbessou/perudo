<?php

namespace App\Handler;

use App\Entity\Player;

class LiarActionIsAvailableHandler
{
    public function checkIfLiarActionIsAvailable(Player $player): void
    {
        $this->notMyTurn($player);
        $this->noPlayerBefore($player);
        $this->notEnoughDice($player);
    }

    private function notMyTurn(Player $player): void
    {
        if (!$player->isMyTurn()) {
            throw new \LogicException('It\'s not your turn to play');
        }
    }

    private function noPlayerBefore(Player $player): void
    {
        if ($player->getGame()->getBets()->count() === 0) {
            throw new \LogicException('You can\'t say "liar", because your the first player to play');
        }
    }

    private function notEnoughDice(Player $player): void
    {
        if ($player->getNumberOfDices() === 0) {
            throw new \LogicException('You can\'t say "liar", because you have not enough dice');
        }
    }
}
