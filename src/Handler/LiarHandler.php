<?php

namespace App\Handler;

use App\Entity\Game;
use App\Entity\Player;

class LiarHandler
{
    private LiarActionIsAvailableHandler $liarActionIsAvailableHandler;
    private PlayerLifeHandler $playerLifeHandler;

    public function __construct(LiarActionIsAvailableHandler $liarActionIsAvailableHandler, PlayerLifeHandler $playerLifeHandler)
    {
        $this->liarActionIsAvailableHandler = $liarActionIsAvailableHandler;
        $this->playerLifeHandler = $playerLifeHandler;
    }

    public function liar(Player $player): Player
    {
        $this->liarActionIsAvailableHandler->checkIfLiarActionIsAvailable($player);
        $looser = $this->choosePlayerWhichLostOneLife($player->getGame());
        $this->playerLifeHandler->decreaseLife(
            $looser
        );

        return $looser;
    }

    private function choosePlayerWhichLostOneLife(Game $game): Player
    {
        $lastDiceNumber = $this->getLastDiceNumber($game);

        return $this->countDiceNumberForAValue($game) < $lastDiceNumber
            ? $this->getLastPlayerToBet($game)
            : $this->getPlayerTellLiar($game);
    }

    private function getLastDiceNumber(Game $game): int
    {
        return $game->getBets()->last()->getDiceNumber();
    }

    private function countDiceNumberForAValue(Game $game): int
    {
        $diceNumberOfDiceValue = 0;
        $lastDiceValue = $this->getLastDiceValue($game);

        foreach ($game->getPlayers() as $player) {
            $counts = array_count_values($player->getDices());
            $diceNumberOfDiceValue += $counts[$lastDiceValue] ?? 0;
        }

        return $diceNumberOfDiceValue;
    }

    private function getLastDiceValue(Game $game): int
    {
        return $game->getBets()->last()->getDiceValue();
    }

    private function getLastPlayerToBet(Game $game): Player
    {
        return $game->getBets()->last()->getPlayer();
    }

    private function getPlayerTellLiar(Game $game): Player
    {
        foreach ($game->getPlayers() as $player) {
            if ($player->isMyTurn()) {
                return $player;
            }
        }

        throw new \LogicException('No player have to play');
    }
}
