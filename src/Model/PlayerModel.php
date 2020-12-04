<?php

namespace App\Model;

use App\Model\Traits\UuidTrait;

class PlayerModel
{
    use UuidTrait;

    public const DICE_COLOR = [
        'red',
        'yellow',
        'green',
        'orange',
        'pruple',
        'blue',
    ];

    private string $pseudo;
    private bool $bot;
    private int $numberOfDices;
    private string $diceColor;
    private array $dices;

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
}
