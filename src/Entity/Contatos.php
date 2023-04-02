<?php

namespace App\Entity;

use App\Repository\ContatosRepository;
use Doctrine\ORM\Mapping as ORM;
use MyProject\Models\Entity\Pessoa;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: ContatosRepository::class)]
class Contatos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    
    #[ORM\ManyToOne(targetEntity: Pessoas::class, inversedBy: "contact")]
    public readonly Pessoas $pessoa;
    public function __construct(

        #[ORM\Column]
        private string $tipo,
    
        #[ORM\Column(length: 255)]
        public string $contact
    
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(bool $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getPessoa_id(): int
    {
        $pessoa_id = $this->pessoa->getId();
        return $pessoa_id;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function setPessoas(Pessoas $pessoa): void
    {
        $this->pessoa = $pessoa;
    }
}
