<?php

namespace App\Tests\Factory;

use App\Factory\PlayerFactory;
use App\Model\PlayerModel;
use PHPUnit\Framework\TestCase;

class PlayerFactoryTest extends TestCase
{
    public function testInitialize()
    {
        $pseudo = 'test';
        $factory = new PlayerFactory();
        $result = $factory->initialize($pseudo, false);
        $this->assertInstanceOf(PlayerModel::class, $result);
        $this->assertEquals($pseudo, $result->getPseudo());
        $this->assertFalse($result->isBot());
    }
}
