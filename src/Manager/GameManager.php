<?php

namespace App\Manager;

use App\Model\GameModel;
use App\Repository\AbstractRepository;

class GameManager
{
    private const TTL = 3600;

    private AbstractRepository $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(GameModel $gameModel): void
    {
        $this->repository->save($gameModel->getUuid(), self::TTL, serialize($gameModel));
    }

    public function get(string $uuid): GameModel
    {
        return unserialize($this->repository->find($uuid));
    }

    public function delete(string $key): GameModel
    {
        return new GameModel();
    }
}
