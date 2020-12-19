<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Validator as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"game:read", "bet:read"}},
 *         "denormaliztion_context"={"groups"={"bet:write"}}
 *     },
 *     itemOperations={
 *         "get"
 *     })
 *     @ApiFilter(SearchFilter::class, properties={"game": "exact"})
 *     @ApiFilter(OrderFilter::class, properties={"id"})
 *     @AppAssert\BetDiceSuperiorPreviousBet
 */
class Bet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({"game:read", "bet:read", "bet:write"})
     */
    private int $id;

    /**
     * @ORM\Column(name="dice_number", type="integer")
     *
     * @Assert\GreaterThanOrEqual(1)
     *
     * @Groups({"game:read", "bet:read", "bet:write"})
     */
    private int $diceNumber;

    /**
     * @ORM\Column(name="dice_value", type="integer")
     *
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(6)
     *
     * @Groups({"game:read", "bet:read", "bet:write"})
     */
    private int $diceValue;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="bets")
     *
     * @Groups({"bet:write"})
     */
    private Game $game;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="bets")
     *
     * @Groups({"bet:read", "bet:write"})
     */
    private Player $player;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDiceNumber(): int
    {
        return $this->diceNumber;
    }

    public function setDiceNumber(int $diceNumber): self
    {
        $this->diceNumber = $diceNumber;

        return $this;
    }

    public function getDiceValue(): int
    {
        return $this->diceValue;
    }

    public function setDiceValue(int $diceValue): self
    {
        $this->diceValue = $diceValue;

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

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validateDiceNumberNotSuperiorToDicesInGame(ExecutionContextInterface $context): void
    {
        $totalDiceNumber = 0;
        foreach ($this->getGame()->getPlayers() as $player) {
            $totalDiceNumber += $player->getNumberOfDices();
        }

        if ($totalDiceNumber < $this->getDiceNumber()) {
            $context->buildViolation('There is not enough dice in game')
                ->atPath('diceNumber')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function validatePlayerWhichBet(ExecutionContextInterface $context): void
    {
        if (!$this->player->isMyTurn()) {
            $context->buildViolation('There is not your turn to play')
                ->atPath('player')
                ->addViolation();
        }
    }
}
