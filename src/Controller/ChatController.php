<?php

namespace App\Controller;

use App\Entity\ChatMessages;
use App\Repository\ChatMessagesRepository;
use Doctrine\ORM\EntityManagerInterface; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chat')]

class ChatController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'chat_index', methods: ['GET'])]
    public function index(ChatMessagesRepository $chatMessagesRepository): JsonResponse
    {
        $chatMessages = $chatMessagesRepository->findAll();
        $data = [];

        foreach ($chatMessages as $chatMessage) {
            $data[] = [
                'id' => $chatMessage->getId(),
                'message' => $chatMessage->getContent(),
                'created_at' => $chatMessage->getCreatedAt()->format('Y-m-d H:i:s'), // Formatowanie daty do stringa
                'user_id' => $chatMessage->getUserId() ? $chatMessage->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
                'board_id' => $chatMessage->getBoardId() ? $chatMessage->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'chat_show', methods: ['GET'])]
    public function show(int $id, ChatMessagesRepository $chatMessagesRepository): JsonResponse
    {
        $chatMessage = $chatMessagesRepository->find($id);

        if (!$chatMessage) {
            return new JsonResponse(['error' => 'Chat message not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $chatMessage->getId(),
            'message' => $chatMessage->getContent(),
            'created_at' => $chatMessage->getCreatedAt()->format('Y-m-d H:i:s'), // Formatowanie daty do stringa
            'user_id' => $chatMessage->getUserId() ? $chatMessage->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
            'board_id' => $chatMessage->getBoardId() ? $chatMessage->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'chat_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['message'])) {
            return new JsonResponse(['error' => 'Message is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $chatMessage = new ChatMessages();
        $chatMessage->setContent($data['message']);
        $chatMessage->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($chatMessage);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $chatMessage->getId()], JsonResponse::HTTP_CREATED);
    }
}
