<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use App\Repository\UsersRepository;
use App\Repository\BoardsRepository;
use Doctrine\ORM\EntityManagerInterface; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]

class TaskController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): JsonResponse
    {
        $tasks = $tasksRepository->findAll();
        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'name' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'user_id' => $task->getUserId() ? $task->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
                'board_id' => $task->getBoardId() ? $task->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(int $id, TasksRepository $tasksRepository): JsonResponse
    {
        $task = $tasksRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $task->getId(),
            'name' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'user_id' => $task->getUserId() ? $task->getUserId()->getId() : null, // Sprawdzanie, czy istnieje user_id
            'board_id' => $task->getBoardId() ? $task->getBoardId()->getId() : null, // Sprawdzanie, czy istnieje board_id
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'task_add', methods: ['POST'])]
    public function add(Request $request, UsersRepository $usersRepository, BoardsRepository $boardsRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Tasks();
        $task->setTitle($data['name']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);

        // Pobierz encję Users na podstawie przekazanego identyfikatora użytkownika
        $user = $usersRepository->find($data['user_id']);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Pobierz encję Boards na podstawie przekazanego identyfikatora tablicy
        $board = $boardsRepository->find($data['board_id']);
        if (!$board) {
            return new JsonResponse(['error' => 'Board not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $task->setUserId($user);
        $task->setBoardId($board); // Przypisz encję Boards do pola board_id

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $task->getId()], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(int $id, Request $request, TasksRepository $tasksRepository, UsersRepository $usersRepository, BoardsRepository $boardsRepository): JsonResponse
    {
        $task = $tasksRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Sprawdź, czy istnieje klucz "name" i przypisz go do pola "title" w encji Tasks
        if (isset($data['name'])) {
            $task->setTitle($data['name']);
        }

        // Sprawdź, czy istnieje klucz "description" i przypisz go do pola "description" w encji Tasks
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }

        // Sprawdź, czy istnieje klucz "status" i przypisz go do pola "status" w encji Tasks
        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        // Pobierz encję Users na podstawie przekazanego identyfikatora użytkownika
        if (isset($data['user_id'])) {
            $user = $usersRepository->find($data['user_id']);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $task->setUserId($user);
        }

        // Pobierz encję Boards na podstawie przekazanego identyfikatora tablicy
        if (isset($data['board_id'])) {
            $board = $boardsRepository->find($data['board_id']);
            if (!$board) {
                return new JsonResponse(['error' => 'Board not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $task->setBoardId($board);
        }

        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete(int $id, TasksRepository $tasksRepository): JsonResponse
    {
        $task = $tasksRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
