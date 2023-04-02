<?php

namespace App\Entity;

use App\Repository\PessoasRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PessoasRepository::class)]
class Pessoas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToMany(targetEntity: Contatos::class, mappedBy: "pessoa", cascade: ["persist", "remove"])]
    private Collection $contact;

    public function __construct(

        #[ORM\Column(length: 255)]
        public string $name,
    
        #[ORM\Column]
        #[Assert\Length(min: 11)]
        public int $cpf

    )
    {
        $this->contact = new ArrayCollection(); 
    }

    public function add_contatos(Contatos $contato)
    {
        $this->contact->add($contato);
        $contato->setPessoas($this);
    }
    
    /** @return collection<Contatos> */
    public function contatos(): Collection
    {
        return $this->contact;
    }
    


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCpf(): int
    {
        return $this->cpf;
    }

    public function setCpf(int $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }
}
