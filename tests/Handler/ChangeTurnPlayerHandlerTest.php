<?php

namespace App\Tests\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Handler\ChangeTurnPlayerHandler;
use App\Manager\GameManager;
use PHPUnit\Framework\TestCase;

class ChangeTurnPlayerHandlerTest extends TestCase
{
    private Game $game;
    private ChangeTurnPlayerHandler $changeTurnPlayerHandler;

    public function setup()
    {
        $game = (new Game())
            ->setNumberOfPlayers(2);
        $player1 = (new Player())
            ->setMyTurn(true);
        $player2 = new Player();
        $player3 = new Player();
        $game->addPlayer($player1);
        $game->addPlayer($player2);
        $game->addPlayer($player3);

        $gameManager = $this->createMock(GameManager::class);

        $changeTurnPlayerHandler = new ChangeTurnPlayerHandler($gameManager);

        $this->game = $game;
        $this->changeTurnPlayerHandler = $changeTurnPlayerHandler;
    }

    public function testChangeTurnPlayer()
    {
        $this->changeTurnPlayerHandler->changeTurnPlayer($this->game);

        $this->assertTrue($this->game->getPlayers()->get(1)->isMyTurn());
        $this->assertFalse($this->game->getPlayers()->get(0)->isMyTurn());
    }

    public function testChangeTurnPlayerWithSpecifyingPlayer()
    {
        $this->changeTurnPlayerHandler->changeTurnPlayer($this->game, 2);

        $this->assertTrue($this->game->getPlayers()->get(2)->isMyTurn());
        $this->assertFalse($this->game->getPlayers()->get(0)->isMyTurn());
    }

    public function testChangeTurnPlayerWithSpecifyingPlayerDoesNotExist()
    {
        try {
            $this->changeTurnPlayerHandler->changeTurnPlayer($this->game, 3);
        } catch (\LogicException $exception) {
            if ($exception) {
                $this->assertInstanceOf(\LogicException::class, $exception);
            } else {
                $this->fail('exception not expected');
            }
        }
    }
}
