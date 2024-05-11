<?php

namespace App\Controller;

use App\Entity\BoardMembers;
use App\Repository\BoardMembersRepository;
use App\Repository\UsersRepository;
use App\Repository\BoardsRepository;
use Doctrine\ORM\EntityManagerInterface; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/board-members')]

class BoardMemberController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'board_member_index', methods: ['GET'])]
    public function index(BoardMembersRepository $boardMembersRepository): JsonResponse
    {
        $board_member = $boardMembersRepository->findAll();
        $data = [];

        foreach ($board_member as $member) {
            $data[] = [
                'id' => $member->getId(),
                'board_id' => $member->getBoardId() ? $member->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
                'user_id' => $member->getUserId() ? $member->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
            ];
        }
         return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'board_member_show', methods: ['GET'])]
    public function showOne(int $id, BoardMembersRepository $boardMembersRepository): JsonResponse
    {
        $board_member = $boardMembersRepository->find($id);

        if (!$board_member) {
            return new JsonResponse(['error' => 'Board member not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $board_member->getId(),
            'board_id' => $board_member->getBoardId() ? $board_member->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
            'user_id' => $board_member->getUserId() ? $board_member->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'board_member_create', methods: ['POST'])]
    public function create(Request $request, UsersRepository $usersRepository, BoardsRepository $boardsRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $board_member = new BoardMembers();
        $board_member->setUserId($usersRepository->find($data['user_id']));
        $board_member->setBoardId($boardsRepository->find($data['board_id']));

        $this->entityManager->persist($board_member);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Board member created!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'board_member_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, BoardMembersRepository $boardMembersRepository, UsersRepository $usersRepository, BoardsRepository $boardsRepository): JsonResponse
    {
        $board_member = $boardMembersRepository->find($id);

        if (!$board_member) {
            return new JsonResponse(['error' => 'Board member not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        empty($data['user_id']) ? true : $board_member->setUserId($usersRepository->find($data['user_id']));
        empty($data['board_id']) ? true : $board_member->setBoardId($boardsRepository->find($data['board_id']));

        $this->entityManager->persist($board_member);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Board member updated!'], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'board_member_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $board_member = $this->entityManager->getRepository(BoardMembers::class)->find($id);

        if (!$board_member) {
            return new JsonResponse(['error' => 'Board member not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($board_member);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Board member deleted'], JsonResponse::HTTP_OK);
    }

    #[Route('/join', name: 'board_member_join', methods: ['POST'])]
    public function joinTable (Request $request, UsersRepository $usersRepository, BoardsRepository $boardsRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $usersRepository->find($data['user_id']);
        $board = $boardsRepository->find($data['board_id']);

        if (!$user || !$board) {
            return new JsonResponse(['error' => 'User or board not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $board_member = new BoardMembers();
        $board_member->setUserId($user);
        $board_member->setBoardId($board);

        $this->entityManager->persist($board_member);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User joined board'], JsonResponse::HTTP_CREATED);
    }
}
