<?php

namespace App\Entity;

use App\Repository\ChatMessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatMessagesRepository::class)]
class ChatMessages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'chatMessages')]
    private ?Boards $board_id = null;

    #[ORM\ManyToOne(inversedBy: 'chatMessages')]
    private ?Users $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getBoardId(): ?Boards
    {
        return $this->board_id;
    }

    public function setBoardId(?Boards $board_id): static
    {
        $this->board_id = $board_id;

        return $this;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
