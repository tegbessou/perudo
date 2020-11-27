<?php

namespace App\Tests\Util;

use App\Util\LaunchDiceUtil;
use PHPUnit\Framework\TestCase;

class LaunchDiceUtilTest extends TestCase
{
    public function testLaunchDices()
    {
        $result = LaunchDiceUtil::launchDices(2);
        $this->assertIsArray($result);
        $this->assertContainsOnly('int', $result);
        $this->assertCount(2, $result);
    }
}
