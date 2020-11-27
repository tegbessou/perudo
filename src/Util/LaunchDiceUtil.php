<?php

namespace App\Util;

class LaunchDiceUtil
{
    public static function launchDices(int $numberOfDices): array
    {
        $dices = [];
        $index = 1;
        while ($index <= $numberOfDices) {
            $dices[] = self::launchDice();
            ++$index;
        }

        return $dices;
    }

    public static function launchDice(): int
    {
        return random_int(1, 6);
    }
}
