<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/users')]
class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UsersRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'nickname' => $user->getNickname(),
                'password' => $user->getPassword(),
                'reset_token' => $user->getResetToken(),
                'reset_token_expires_at' => $user->getResetTokenExpiresAt(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(Users $user): JsonResponse
    {
        $data = [
            'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'nickname' => $user->getNickname(),
                'password' => $user->getPassword(),
                'reset_token' => $user->getResetToken(),
                'reset_token_expires_at' => $user->getResetTokenExpiresAt(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/', name: 'user_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Sprawdź, czy przesłane dane zawierają wymagane pola
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['nickname'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Stwórz nowego użytkownika na podstawie danych przesłanych w żądaniu
        $user = new Users();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setNickname($data['nickname']);
        $encoded = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($encoded);
        $user->setResetToken($data['reset_token']);
        $user->setResetTokenExpiresAt($data['reset_token_expires_at']);

        // Zapisz nowego użytkownika do bazy danych
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User added successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'user_edit', methods: ['PUT'])]
    public function edit(Users $user, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Pobierz dane przesłane w żądaniu
        $data = json_decode($request->getContent(), true);

        // Zaktualizuj dane użytkownika
        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setNickname($data['nickname'] ?? $user->getNickname());
        $user->setPassword($data['password'] ?? $user->getPassword());
        $user->setResetToken($data['reset_token'] ?? $user->getResetToken());
        $user->setResetTokenExpiresAt($data['reset_token_expires_at'] ?? $user->getResetTokenExpiresAt());

        // Zapisz zmiany w bazie danych
        $entityManager->flush();

        return new JsonResponse(['message' => 'User updated successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(Users $user): JsonResponse
    {
        // Usuń użytkownika
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request, UsersRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['username' => $data['username']]);

        if (!$user) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            return new JsonResponse(['error' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['message' => 'Login successful'], JsonResponse::HTTP_OK);
    }

    #[Route('/reset-password', name: 'user_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UsersRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour'));

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Password reset link sent'], JsonResponse::HTTP_OK);
    }

    #[Route('/change-password', name: 'user_change_password', methods: ['POST'])]
    public function changePassword(Request $request, UsersRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['reset_token']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['reset_token' => $data['reset_token']]);

        if (!$user) {
            return new JsonResponse(['error' => 'Invalid reset token'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($user->getResetTokenExpiresAt() < new \DateTimeImmutable()) {
            return new JsonResponse(['error' => 'Reset token expired'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $encoded = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($encoded);
        $user->setResetToken(null);
        $user->setResetTokenExpiresAt(null);

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Password changed successfully'], JsonResponse::HTTP_OK);
    }
}
