<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reset_token = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $reset_token_expires_at = null;

    /**
     * @var Collection<int, Boards>
     */
    #[ORM\OneToMany(targetEntity: Boards::class, mappedBy: 'user_id')]
    private Collection $boards;

    /**
     * @var Collection<int, BoardMembers>
     */
    #[ORM\OneToMany(targetEntity: BoardMembers::class, mappedBy: 'user_id')]
    private Collection $boardMembers;

    /**
     * @var Collection<int, Tasks>
     */
    #[ORM\OneToMany(targetEntity: Tasks::class, mappedBy: 'user_id')]
    private Collection $tasks;

    /**
     * @var Collection<int, ChatMessages>
     */
    #[ORM\OneToMany(targetEntity: ChatMessages::class, mappedBy: 'user_id')]
    private Collection $chatMessages;

    public function __construct()
    {
        $this->boards = new ArrayCollection();
        $this->boardMembers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): static
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->reset_token_expires_at;
    }

    public function setResetTokenExpiresAt(?\DateTimeImmutable $reset_token_expires_at): static
    {
        $this->reset_token_expires_at = $reset_token_expires_at;

        return $this;
    }

    /**
     * @return Collection<int, Boards>
     */
    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addBoard(Boards $board): static
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setUserId($this);
        }

        return $this;
    }

    public function removeBoard(Boards $board): static
    {
        if ($this->boards->removeElement($board)) {
            // set the owning side to null (unless already changed)
            if ($board->getUserId() === $this) {
                $board->setUserId(null);
            }
        }

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
            $boardMember->setUserId($this);
        }

        return $this;
    }

    public function removeBoardMember(BoardMembers $boardMember): static
    {
        if ($this->boardMembers->removeElement($boardMember)) {
            // set the owning side to null (unless already changed)
            if ($boardMember->getUserId() === $this) {
                $boardMember->setUserId(null);
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
            $task->setUserId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUserId() === $this) {
                $task->setUserId(null);
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
            $chatMessage->setUserId($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessages $chatMessage): static
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            // set the owning side to null (unless already changed)
            if ($chatMessage->getUserId() === $this) {
                $chatMessage->setUserId(null);
            }
        }

        return $this;
    }
}
