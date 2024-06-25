<?php

namespace App\Project;

use App\Project\Card;

/**
 * Class representing a hand of cards.
 *
 * Encapsulate methods related to a hand object.
 *
 * @property array $cards Contains all cards in the hand.
 * @property int $bet The value of the bet assign to the hand.
 * @property bool $isBlackjack Define is a hand has a blackjack.
 * @property float $winning The value of the winning according to the bet and the value of the hand.
 *
 */
class Hand
{
    /**
     * @var array<Card> $cards
     */
    private array $cards;
    private int $bet;
    private bool $isBlackjack;
    private float $winning;

    /**
     * Constructor for Hand class.
     *
     * Assign array $cards to an empty array.
     * Assign int $bet to an initial value of 0.
     * Assign bool $isBlackjack to false.
     * Assign int $winning to an initial value of 0.
     */
    public function __construct()
    {
        $this->cards = [];
        $this->bet = 0;
        $this->isBlackjack = false;
        $this->winning = 0;
    }

    /**
     * Get all the cards from the shoe.
     * @return array<Card> $cards
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Get the bet set on the hand.
     * @return int $bet The value of the bet set.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Get if the hand is blackjack, return true or false.
     * @return bool $isBlackjack The boolean value of the hand related to blackjack.
     */
    public function isBlackjack(): bool
    {
        return $this->isBlackjack;
    }

    /**
     * Get the value of the winning of the hand.
     * @return float $winning The value of the winning after calculation of hand value.
     */
    public function getWinning(): float
    {
        return $this->winning;
    }

    /**
     * Add a new card object in array $cards.
     * @param Card $card An instans of Card class.
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Set a bet for the hand. Assign new value to int $bet.
     * @param int $amount The value of the bet to set.
     */
    public function placeBet(int $amount): void
    {
        $this->bet = $amount;
    }

    /**
     * Assign boolean $isBlackjack to true.
     */
    public function hasBlackjack(): void
    {
        $this->isBlackjack = true;
    }

    /**
     * Set the winning value for the hand. Assign a new value to int $winning.
     * @param float $winning The value won for the hand.
     */
    public function placeWinning(float $winning): void
    {
        $this->winning = $winning;
    }

    /**
     * Calculate the value for the hand. Use the method getCardValue from Card class.
     * If a hand has an Ace (11) and the hand value gets over 21, substracts the amount with 10.
     * @return int $totalValue The total amount value for the hand.
     */
    public function handValue(): int
    {
        $totalValue = 0;
        $aceCount = 0;

        foreach ($this->cards as $card) {
            $totalValue += $card->getCardValue();

            if ($card->getCardValue() === 11) {
                $aceCount++;
            }
        }
        while ($totalValue > 21 && $aceCount > 0) {
            $totalValue -= 10;
            $aceCount--;
        }
        return $totalValue;
    }

    /**
     * Get the string representation of the array $cards.
     * @return array<string>
     */
    public function toString(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->toString();
        }

        return $values;
    }
}
