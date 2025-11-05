<?php

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 2500)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $fileType = null;

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(?string $fileType): void
    {
        $this->fileType = $fileType;
    }

    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'fileName')]
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $behanceLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagramLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $externalWebsiteLink = null;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private bool $isBehanceLinkActive = false;

    #[ORM\Column(type: 'boolean',  options: ["default" => false])]
    private bool $isInstagramLinkActive = false;

    #[ORM\Column(type: 'boolean',  options: ["default" => false])]
    private bool $isFacebookLinkActive = false;

    #[ORM\Column(type: 'boolean',  options: ["default" => false])]
    private bool $isExternalWebsiteLinkActive = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;

        if (null !== $file) {
            // Only update the fileName if a new file is uploaded
            $this->fileName = $file->getFilename(); // Or generate a unique filename
            $this->updatedAt = new \DateTimeImmutable();
        } else {
            // Set fileName to null if the file is deleted
            $this->fileName = null;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBehanceLink(): ?string
    {
        return $this->behanceLink;
    }

    public function setBehanceLink(?string $behanceLink): void
    {
        $this->behanceLink = $behanceLink;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): void
    {
        $this->instagramLink = $instagramLink;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): void
    {
        $this->facebookLink = $facebookLink;
    }

    public function getExternalWebsiteLink(): ?string
    {
        return $this->externalWebsiteLink;
    }

    public function setExternalWebsiteLink(?string $externalWebsiteLink): void
    {
        $this->externalWebsiteLink = $externalWebsiteLink;
    }

    public function isBehanceLinkActive(): bool
    {
        return $this->isBehanceLinkActive;
    }

    public function setIsBehanceLinkActive(bool $isBehanceLinkActive): void
    {

            $this->isBehanceLinkActive = $isBehanceLinkActive;

    }

    public function isInstagramLinkActive(): bool
    {
        return $this->isInstagramLinkActive;
    }

    public function setIsInstagramLinkActive(bool $isInstagramLinkActive): void
    {
        $this->isInstagramLinkActive = $isInstagramLinkActive;
    }

    public function isFacebookLinkActive(): bool
    {
        return $this->isFacebookLinkActive;
    }

    public function setIsFacebookLinkActive(bool $isFacebookLinkActive): void
    {
        $this->isFacebookLinkActive = $isFacebookLinkActive;
    }

    public function isExternalWebsiteLinkActive(): bool
    {
        return $this->isExternalWebsiteLinkActive;
    }

    public function setIsExternalWebsiteLinkActive(bool $isExternalWebsiteLinkActive): void
    {
        $this->isExternalWebsiteLinkActive = $isExternalWebsiteLinkActive;
    }
}
