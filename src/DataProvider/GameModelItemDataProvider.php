<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Manager\GameManager;
use App\Model\GameModel;

class GameModelItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private GameManager $gameManager;

    public function __construct(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return GameModel::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?GameModel
    {
        return $this->gameManager->get($id);
    }
}
