<?php

namespace App\Project;

use App\Project\Hand;

/**
 * Class representing a Player.
 *
 * Encapsulate methods related to a player object.
 *
 * @property float $balance The value of the balance the player has.
 * @property array $hands The different hands a player can have.
 * @property string $name The name of the player.
 *
 */
class Player
{
    private const STARTING_BALANCE = 155;
    private float $balance;
    private string $name;

    /**
     * @var array<Hand> $hands
     */
    private array $hands;

    /**
     * Constructor for Player class.
     *
     * Assign float $balance to the constant value STARTING_BALANCE.
     * Assign array $hands to an empty array.
     * Assign string $name to an empty string.
     */
    public function __construct()
    {
        $this->balance = self::STARTING_BALANCE;
        $this->hands = [];
        $this->name = "";
    }

    /**
     * Get the balance of the player.
     * @return float The value of the balance.
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Get all the hands object from the $hands array.
     * @return array<Hand> The hands of the player.
     */
    public function getHands(): array
    {
        return $this->hands;
    }

    /**
     * Set the player name.
     * @param string $name The name of the player.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the player name.
     * @return string $name The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Add a new hand object in array $hands.
     * @param Hand $hand An instans of Hand class.
     */
    public function addHand(Hand $hand): void
    {
        array_push($this->hands, $hand);
    }

    /**
     * Adjust the balance of the player.
     * @param float $sumToAdd The value to add to the balance.
     */
    public function adjustBalance(float $sumToAdd): void
    {
        $this->balance += $sumToAdd;
    }

    /**
     * Reset the array $hands to an empty array.
     */
    public function resetHand(): void
    {
        $this->hands = [];
    }
}
