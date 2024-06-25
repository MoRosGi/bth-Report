<?php

namespace App\Project;

use App\Project\Hand;

/**
 * Class representing a Dealer.
 *
 * Encapsulate methods related to a dealer object.
 *
 * @property Hand $hand The hand of the dealer, an instance of Hand class.
 */
class Dealer
{
    private Hand $hand;

    /**
     * Constructor for Dealer class.
     *
     * Assign $hand to a new hand object.
     */
    public function __construct()
    {
        $this->hand = new Hand();
    }

    // public function addHand(Hand $hand): void
    // {
    //     $this->hand = $hand;
    // }

    /**
     * Get the hand of the dealer.
     * @return Hand The hand of the dealer.
     */
    public function getHand(): Hand
    {
        return $this->hand;
    }

    /**
     * Reset the $hand to a new hand object.
     */
    public function resetHand(): void
    {
        $this->hand = new Hand();
    }
}
