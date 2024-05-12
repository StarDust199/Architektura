<?php

namespace App\Entity;

use App\Repository\BoardsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardsRepository::class)]
class Boards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'boards')]
    private ?Users $user_id = null;

    /**
     * @var Collection<int, BoardMembers>
     */
    #[ORM\OneToMany(targetEntity: BoardMembers::class, mappedBy: 'board_id', cascade: ['remove'])]
    private Collection $boardMembers;

    /**
     * @var Collection<int, Tasks>
     */
    #[ORM\OneToMany(targetEntity: Tasks::class, mappedBy: 'board_id', cascade: ['remove'])]
    private Collection $tasks;

    /**
     * @var Collection<int, ChatMessages>
     */
    #[ORM\OneToMany(targetEntity: ChatMessages::class, mappedBy: 'board_id', cascade: ['remove'])]
    private Collection $chatMessages;

    public function __construct()
    {
        $this->boardMembers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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

    /**
     * @return Collection<int, BoardMembers>
     */
    public function getBoardMembers(): Collection
    {
        return $this->boardMembers;
    }

    public function addBoardMember(BoardMembers $boardMember): static
    {
        if (!$this->boardMembers->contains($boardMember)) {
            $this->boardMembers->add($boardMember);
            $boardMember->setBoardId($this);
        }

        return $this;
    }

    public function removeBoardMember(BoardMembers $boardMember): static
    {
        if ($this->boardMembers->removeElement($boardMember)) {
            // set the owning side to null (unless already changed)
            if ($boardMember->getBoardId() === $this) {
                $boardMember->setBoardId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setBoardId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getBoardId() === $this) {
                $task->setBoardId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChatMessages>
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessages $chatMessage): static
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->add($chatMessage);
            $chatMessage->setBoardId($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessages $chatMessage): static
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            // set the owning side to null (unless already changed)
            if ($chatMessage->getBoardId() === $this) {
                $chatMessage->setBoardId(null);
            }
        }

        return $this;
    }
}
