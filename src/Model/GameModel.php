<?php

namespace App\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Model\Traits\UuidTrait;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class GameModel implements RedisStorageInterface
{
    use UuidTrait;

    private string $creator;
    private string $creatorColor;
    private ?int $numberOfPlayers = 2;
    private array $players;

    public function getCreator(): string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreatorColor(): string
    {
        return $this->creatorColor;
    }

    public function setCreatorColor(string $creatorColor): self
    {
        $this->creatorColor = $creatorColor;

        return $this;
    }

    public function getNumberOfPlayers(): ?int
    {
        return $this->numberOfPlayers;
    }

    public function setNumberOfPlayers(?int $numberOfPlayers): self
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function setPlayers(array $players): self
    {
        $this->players = $players;

        return $this;
    }
}
