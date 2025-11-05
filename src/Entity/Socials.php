<?php

namespace App\Entity;

use App\Repository\SocialsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialsRepository::class)]
class Socials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $behance = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $isEmailVisible = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getBehance(): ?string
    {
        return $this->behance;
    }

    public function setBehance(?string $behance): static
    {
        $this->behance = $behance;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isEmailVisible(): ?bool
    {
        return $this->isEmailVisible;
    }

    public function setIsEmailVisible(bool $isEmailVisible): static
    {
        $this->isEmailVisible = $isEmailVisible;

        return $this;
    }
}
