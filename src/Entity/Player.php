<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     */
    private int $id;

    /**
     * @ORM\Column(name="pseudo", type="string")
     */
    private string $pseudo;

    /**
     * @ORM\Column(name="bot", type="boolean")
     */
    private bool $bot;

    /**
     * @ORM\Column(name="number_of_dices", type="integer")
     */
    private int $numberOfDices;

    /**
     * @ORM\Column(name="dice_color", type="string")
     */
    private string $diceColor;

    /**
     * @ORM\Column(name="dices", type="array")
     */
    private array $dices;

    /**
     * @ORM\Column(name="my_turn", type="boolean")
     */
    private bool $myTurn = false;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="players")
     */
    private Game $game;

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
}
