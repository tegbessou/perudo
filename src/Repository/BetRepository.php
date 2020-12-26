<?php

namespace App\Repository;

use App\Entity\Bet;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bet::class);
    }

    public function deleteAllBetForGame(Game $game): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $deleteQuery = 'DELETE FROM bet WHERE game_id = :gameId';
        $stmt = $connection->prepare($deleteQuery);
        $stmt->bindValue('gameId', $game->getId());
        $stmt->execute();
    }
}
