<?php

namespace App\Entity;

use App\Repository\BoardMembersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardMembersRepository::class)]
class BoardMembers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'boardMembers')]
    private ?Users $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'boardMembers')]
    private ?Boards $board_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBoardId(): ?Boards
    {
        return $this->board_id;
    }

    public function setBoardId(?Boards $board_id): static
    {
        $this->board_id = $board_id;

        return $this;
    }
}
