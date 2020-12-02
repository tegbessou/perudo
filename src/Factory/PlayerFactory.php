<?php

namespace App\Factory;

use App\Model\PlayerModel;
use App\Util\LaunchDiceUtil;

class PlayerFactory
{
    public const BOT_PSEUDO = 'Bot ';
    public const DEFAULT_DICE_NUMBER = 5;

    public function initialize(string $pseudo, bool $isBot, string $diceColor): PlayerModel
    {
        return (new PlayerModel())
            ->setNumberOfDices(self::DEFAULT_DICE_NUMBER)
            ->setPseudo($pseudo)
            ->setBot($isBot)
            ->setDiceColor($diceColor)
            ->setDices(LaunchDiceUtil::launchDices(self::DEFAULT_DICE_NUMBER));
    }
}
