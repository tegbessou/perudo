<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\BetRepository;

class BetManager
{
    private BetRepository $repository;

    public function __construct(BetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteAllBetForGame(Game $game): void
    {
        $this->repository->deleteAllBetForGame($game);
    }
}
