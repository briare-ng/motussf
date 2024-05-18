<?php

namespace App\Entity;

use App\Repository\DictionaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DictionaryRepository::class)]
class Dictionary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numberLetters = null;

    #[ORM\Column(length: 50)]
    private ?string $word = null;

    /**
     * @var Collection<int, Scores>
     */
    #[ORM\OneToMany(targetEntity: Scores::class, mappedBy: 'word')]
    private Collection $scores;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberLetters(): ?int
    {
        return $this->numberLetters;
    }

    public function setNumberLetters(int $numberLetters): static
    {
        $this->numberLetters = $numberLetters;

        return $this;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): static
    {
        $this->word = $word;

        return $this;
    }

    /**
     * @return Collection<int, Scores>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Scores $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setWord($this);
        }

        return $this;
    }

    public function removeScore(Scores $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getWord() === $this) {
                $score->setWord(null);
            }
        }

        return $this;
    }
}
