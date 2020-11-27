<?php

namespace App\Tests\Handler;

use App\Factory\PlayerFactory;
use App\Handler\NewPlayerHandler;
use App\Model\GameModel;
use PHPUnit\Framework\TestCase;

class NewPlayerHandlerTest extends TestCase
{
    private NewPlayerHandler $newPlayerHandler;
    private GameModel $gameModel;

    public function setUp()
    {
        $factory = new PlayerFactory();
        $this->gameModel = (new GameModel())
            ->setNumberOfPlayers(2)
            ->setCreator('pedro')
        ;
        $this->newPlayerHandler = new NewPlayerHandler($factory);
    }

    public function testCreatePlayers()
    {
        $players = $this->newPlayerHandler->createPlayers($this->gameModel);
        $this->assertIsArray($players);
        $this->assertEquals('pedro', $players[0]->getPseudo());
        $this->assertFalse($players[0]->isBot());
        $this->assertEquals('Bot 1', $players[1]->getPseudo());
        $this->assertTrue($players[1]->isBot());
    }
}
