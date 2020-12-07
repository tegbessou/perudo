<?php

namespace App\Tests\Factory;

use App\Entity\Player;
use App\Factory\PlayerFactory;
use PHPUnit\Framework\TestCase;

class PlayerFactoryTest extends TestCase
{
    public function testInitialize()
    {
        $pseudo = 'test';
        $factory = new PlayerFactory();
        $result = $factory->initialize($pseudo, false, 'red');
        $this->assertInstanceOf(Player::class, $result);
        $this->assertEquals($pseudo, $result->getPseudo());
        $this->assertEquals('red', $result->getDiceColor());
        $this->assertFalse($result->isBot());
    }
}
