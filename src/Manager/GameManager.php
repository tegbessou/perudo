<?php

namespace App\Manager;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameManager
{
    private GameRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, GameRepository $repository)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function create(Game $game): void
    {
        $this->entityManager->persist($game);
        $this->save();
    }

    public function find(int $id): ?object
    {
        return $this->repository->find($id);
    }

    public function save(): void
    {
        $this->entityManager->flush();
    }
}
