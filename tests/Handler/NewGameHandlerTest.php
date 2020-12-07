<?php

namespace App\Test\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Handler\NewGameHandler;
use App\Handler\NewPlayerHandler;
use PHPUnit\Framework\TestCase;

class NewGameHandlerTest extends TestCase
{
    public function testAssignPlayer()
    {
        $game = (new Game())
            ->setNumberOfPlayers(2);

        $player1 = (new Player())
            ->setPseudo('pedro')
            ->setBot(false);

        $player2 = (new Player())
            ->setPseudo('Bot 1')
            ->setBot(true);
        $game->addPlayer($player2);

        $newPlayerHandler = $this->createMock(NewPlayerHandler::class);
        $newPlayerHandler
            ->method('createCreator')
            ->willReturn($player1);
        $newPlayerHandler->method('createBotPlayers')
            ->willReturn($game);

        $newGameHandler = new NewGameHandler($newPlayerHandler);
        $newGameHandler->setup($game, 'pedro', 'blue');

        $this->assertCount(2, $game->getPlayers());
    }
}
