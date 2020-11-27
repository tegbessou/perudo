<?php

namespace App\Test\Handler;

use App\Handler\NewGameHandler;
use App\Handler\NewPlayerHandler;
use App\Model\GameModel;
use App\Model\PlayerModel;
use PHPUnit\Framework\TestCase;

class NewGameHandlerTest extends TestCase
{
    public function testAssignPlayer()
    {
        $game = (new GameModel())
            ->setCreator('pedro')
            ->setNumberOfPlayers(2);

        $player1 = (new PlayerModel())
            ->setPseudo('pedro')
            ->setBot(false);

        $player2 = (new PlayerModel())
            ->setPseudo('Bot 1')
            ->setBot(true);

        $newPlayerHandler = $this->createMock(NewPlayerHandler::class);
        $newPlayerHandler
            ->method('createPlayers')
            ->willReturn(
                [
                    $player1,
                    $player2,
                ]
            );

        $newGameHandler = new NewGameHandler($newPlayerHandler);
        $game = $newGameHandler->assignPlayer($game);

        $this->assertIsArray($game->getPlayers());
        $this->assertCount(2, $game->getPlayers());
    }
}
