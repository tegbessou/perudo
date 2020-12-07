<?php

namespace App\Tests\Handler;

use App\Entity\Game;
use App\Entity\Player;
use App\Factory\PlayerFactory;
use App\Handler\NewPlayerHandler;
use PHPUnit\Framework\TestCase;

class NewPlayerHandlerTest extends TestCase
{
    private NewPlayerHandler $newPlayerHandler;
    private Game $game;

    public function setUp()
    {
        $factory = new PlayerFactory();
        $this->game = (new Game())
            ->setNumberOfPlayers(2)
        ;
        $this->newPlayerHandler = new NewPlayerHandler($factory);
    }

    public function testCreateBotPlayers()
    {
        $creator = $this->newPlayerHandler->createCreator('pedro', 'blue');
        $this->game->addPlayer($creator);

        $game = $this->newPlayerHandler->createBotPlayers($this->game);
        $this->assertEquals('Bot 1', $game->getPlayers()->get(1)->getPseudo());
        $this->assertTrue($game->getPlayers()->get(1)->isBot());
        $this->assertNotEquals('blue', $game->getPlayers()->get(1)->getDiceColor());
        $this->assertTrue(in_array($game->getPlayers()->get(1)->getDiceColor(), Player::DICE_COLOR));
    }

    public function testCreateCreator()
    {
        $player = $this->newPlayerHandler->createCreator('pedro', 'blue');
        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('pedro', $player->getPseudo());
        $this->assertFalse($player->isBot());
        $this->assertEquals('blue', $player->getDiceColor());
    }
}
