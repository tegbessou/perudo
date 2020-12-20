<?php

namespace App\Tests\Behat;

use Behat\Mink\Exception\ExpectationException;
use WebDriver\Exception\StaleElementReference;

trait WaitUntilTrait
{
    private function waitUntil(int $seconds, callable $callback): void
    {
        $startTime = time();

        do {
            usleep(100000);
            try {
                $callback();

                return;
            } catch (ExpectationException | StaleElementReference $e) {
                /* Intentionally leave blank */
                // assume page reloaded whilst we were still waiting
            }
        } while (time() - $startTime < $seconds);

        $callback();
    }
}
