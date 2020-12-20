<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"game:read", "player:read"}}
 *     },
 *     collectionOperations={
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     })
 *     @ApiFilter(SearchFilter::class, properties={"game": "exact"})
 *     @ApiFilter(BooleanFilter::class, properties={"myTurn"})
 */
class Player
{
    public const DICE_COLOR = [
        'red',
        'purple',
        'yellow',
        'orange',
        'blue',
        'green',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({"game:read", "player:read"})
     */
    private int $id;

    /**
     * @ORM\Column(name="pseudo", type="string")
     *
     * @Groups({"game:read", "player:read"})
     */
    private string $pseudo;

    /**
     * @ORM\Column(name="bot", type="boolean")
     *
     * @Groups({"game:read", "player:read"})
     */
    private bool $bot;

    /**
     * @ORM\Column(name="number_of_dices", type="integer")
     *
     * @Groups({"game:read", "player:read"})
     */
    private int $numberOfDices;

    /**
     * @ORM\Column(name="dice_color", type="string")
     *
     * @Groups({"game:read", "player:read"})
     */
    private string $diceColor;

    /**
     * @ORM\Column(name="dices", type="array")
     *
     * @Groups({"game:read", "player:read"})
     */
    private array $dices;

    /**
     * @ORM\Column(name="my_turn", type="boolean")
     *
     * @Groups({"game:read", "player:read"})
     */
    private bool $myTurn = false;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="players")
     */
    private Game $game;

    /**
     * @ORM\OneToMany(targetEntity="Bet", mappedBy="player")
     */
    private Collection $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isBot(): bool
    {
        return $this->bot;
    }

    public function setBot(bool $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function getNumberOfDices(): int
    {
        return $this->numberOfDices;
    }

    public function setNumberOfDices(int $numberOfDices): self
    {
        $this->numberOfDices = $numberOfDices;

        return $this;
    }

    public function getDiceColor(): string
    {
        return $this->diceColor;
    }

    public function setDiceColor(string $diceColor): self
    {
        $this->diceColor = $diceColor;

        return $this;
    }

    public function getDices(): array
    {
        return $this->dices;
    }

    public function setDices(array $dices): self
    {
        $this->dices = $dices;

        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function isMyTurn(): bool
    {
        return $this->myTurn;
    }

    public function setMyTurn(bool $myTurn): self
    {
        $this->myTurn = $myTurn;

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
            $bet->setPlayer($this);
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
