<?php

namespace App\Entity;

use App\Repository\PlayerStatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerStatsRepository::class)]
class PlayerStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $balance = null;

    #[ORM\Column(nullable: true)]
    private ?float $winning = null;

    #[ORM\Column(nullable: true)]
    private ?int $bet = null;

    /**
     * @var Collection<int, GameStats>
     */
    #[ORM\OneToMany(targetEntity: GameStats::class, mappedBy: 'player')]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getWinning(): ?float
    {
        return $this->winning;
    }

    public function setWinning(?float $winning): static
    {
        $this->winning = $winning;

        return $this;
    }

    public function getBet(): ?int
    {
        return $this->bet;
    }

    public function setBet(?int $bet): static
    {
        $this->bet = $bet;

        return $this;
    }

    /**
     * @return Collection<int, GameStats>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(GameStats $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setPlayer($this);
        }

        return $this;
    }

    public function removeGame(GameStats $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPlayer() === $this) {
                $game->setPlayer(null);
            }
        }

        return $this;
    }
}
