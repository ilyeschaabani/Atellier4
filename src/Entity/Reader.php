<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Book;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\ManyToMany(targetEntity: book::class, inversedBy: 'readers')]
    private Collection $id_book;

    public function __construct()
    {
        $this->id_book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, book>
     */
    public function getIdBook(): Collection
    {
        return $this->id_book;
    }

    public function addIdBook(book $idBook): static
    {
        if (!$this->id_book->contains($idBook)) {
            $this->id_book->add($idBook);
        }

        return $this;
    }

    public function removeIdBook(book $idBook): static
    {
        $this->id_book->removeElement($idBook);

        return $this;
    }
}
