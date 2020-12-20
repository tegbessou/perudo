<?php

namespace App\Tests\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Handler\ChangeTurnPlayerHandler;
use App\Manager\GameManager;
use PHPUnit\Framework\TestCase;

class ChangeTurnPlayerHandlerTest extends TestCase
{
    public function testChangeTurnPlayer()
    {
        $game = (new Game())
            ->setNumberOfPlayers(2);
        $player1 = (new Player())
            ->setMyTurn(true);
        $player2 = new Player();
        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $gameManager = $this->createMock(GameManager::class);

        $changeTurnPlayerHandler = new ChangeTurnPlayerHandler($gameManager);
        $changeTurnPlayerHandler->changeTurnPlayer($game);

        $this->assertTrue($game->getPlayers()->get(1)->isMyTurn());
        $this->assertFalse($game->getPlayers()->get(0)->isMyTurn());
    }
}
