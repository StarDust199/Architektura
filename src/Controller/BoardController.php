<?php

namespace App\Controller;

use App\Entity\Boards;
use App\Repository\BoardsRepository;
use Doctrine\ORM\EntityManagerInterface; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boards')]

class BoardController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'board_index', methods: ['GET'])]
    public function index(BoardsRepository $boardsRepository): JsonResponse
    {
        $boards = $boardsRepository->findAll();
        $data = [];

        foreach ($boards as $board) {
            $data[] = [
                'id' => $board->getId(),
                'title' => $board->getTitle(),
                'created_at' => $board->getCreatedAt(),
                'user_id' => $board->getUserId(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'board_show', methods: ['GET'])]
    public function show(Boards $board): JsonResponse
    {
        $data = [
            'id' => $board->getId(),
            'title' => $board->getTitle(),
            'created_at' => $board->getCreatedAt(),
            'user_id' => $board->getUserId(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'board_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'])) {
            return new JsonResponse('Missing required data', JsonResponse::HTTP_BAD_REQUEST);
        }

        $board = new Boards();
        $board->setTitle($data['title']);
        $board->setCreatedAt(new \DateTimeImmutable()); // Ustawienie aktualnej daty

        $this->entityManager->persist($board);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Board added successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'board_edit', methods: ['PUT'])]
    public function edit(Boards $boards, Request $request, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'])) {
            return new JsonResponse('Missing required data', JsonResponse::HTTP_BAD_REQUEST);
        }

        $boards->setTitle($data['title']);
        $entityManagerInterface->flush();

        return new JsonResponse(['message' => 'Board updated successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'board_delete', methods: ['DELETE'])]
    public function delete(Boards $boards): JsonResponse
    {
        $this->entityManager->remove($boards);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Board deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}/members', name: 'board_members', methods: ['GET'])]
    public function members(Boards $board): JsonResponse
    {
        $members = $board->getBoardMembers();
        $data = [];

        foreach ($members as $member) {
            $data[] = [
                'id' => $member->getId(),
                'user_id' => $member->getUserId() ? $member->getUserId()->getId() : null,
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}/messages', name: 'board_messages', methods: ['GET'])]
    public function getMessages(Boards $board): JsonResponse
    {
        $messages = $board->getChatMessages();
        $data = [];

        foreach ($messages as $message) {
            $data[] = [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'created_at' => $message->getCreatedAt(),
                'user_id' => $message->getUserId() ? $message->getUserId()->getId() : null,
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}