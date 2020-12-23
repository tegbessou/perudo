<?php

namespace App\Handler;

use App\Entity\Player;

class PlayerLifeHandler
{
    public function decreaseLife(Player $player): void
    {
        $player->setNumberOfDices($player->getNumberOfDices() - 1);
    }
}
