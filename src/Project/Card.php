<?php

namespace App\Project;

/**
 * Class representing a card.
 *
 * Encapsulate methods related to a card object.
 *
 * @property string $suit The suit of the card.
 * @property string $value The value of the card.
 * @property bool $faceUp Define if a card is op or down.
 */
class Card
{
    private const FACE_VALUES = [
        'Jack' => 10,
        'Queen' => 10,
        'King' => 10,
        'Ace' => 11
    ];

    private string $suit;
    private string $value;
    private bool $faceUp;

    /**
     * Constructor for Card class.
     *
     * Assign values for suit and value.
     * Assign bool $faceUp to True.
     *
     * @param string $suit The suit of the card.
     * @param string $value The value of the card.
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->faceUp = true;
    }

    /**
     * Return the value of the card, if card is a face, return the value from FACE_VALUES.
     * @return int $value of the card.
     */
    public function getCardValue(): int
    {
        if (array_key_exists($this->value, self::FACE_VALUES)) {
            return self::FACE_VALUES[$this->value];
        }

        return (int)$this->value;
    }

    /**
     * Get the string representation of the card.
     * @return string
     */
    public function toString(): string
    {
        return "{$this->value} of {$this->suit}";
    }

    /**
     * Flip the card by changing the bool $faceUp.
     */
    public function flipCard(): void
    {
        $this->faceUp = !$this->faceUp;
    }

    /**
     * Get the name path to get the card from image file.
     * @return string
     */
    public function getImageLink(): string
    {
        if (!$this->faceUp) {
            return "back";
        }
        return strtolower("{$this->suit}_{$this->value}");
    }
}
