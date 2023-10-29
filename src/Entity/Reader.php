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

    // MANY TO MANY RELATION WITH BOOK ENTITY BUT BOOK ID IS CALLED REF
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readers')]
    #[ORM\JoinTable(name: 'book_reader')]
    #[ORM\InverseJoinColumn(name: 'book_id', referencedColumnName: 'ref')]
    #[ORM\JoinColumn(name: 'reader_id', referencedColumnName: 'id')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getbooks(): ?Collection
    {
        return $this->books;
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
        return $this->books;
    }

    public function addIdBook(book $idBook): static
    {
        if (!$this->books->contains($idBook)) {
            $this->books->add($idBook);
        }

        return $this;
    }

    public function removeIdBook(book $idBook): static
    {
        $this->books->removeElement($idBook);

        return $this;
    }
 
}
