<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"game:read"}},
 *     },
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * ))
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({"game:read"})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     *
     * @Groups({"game:read"})
     */
    private Player $creator;

    /**
     * @ORM\Column(name="number_of_players", type="integer")
     *
     * @Groups({"game:read"})
     */
    private int $numberOfPlayers = 2;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="game", cascade={"persist"})
     *
     * @Groups({"game:read"})
     */
    private Collection $players;

    /**
     * @ORM\OneToMany(targetEntity="Bet", mappedBy="game", cascade={"persist"})
     *
     * @Groups({"game:read"})
     */
    private Collection $bets;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->bets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreator(): Player
    {
        return $this->creator;
    }

    public function setCreator(Player $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getNumberOfPlayers(): int
    {
        return $this->numberOfPlayers;
    }

    public function setNumberOfPlayers(int $numberOfPlayers): self
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }

    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets[] = $bet;
            $bet->setGame($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->contains($bet)) {
            $this->bets->removeElement($bet);
        }

        return $this;
    }
}
