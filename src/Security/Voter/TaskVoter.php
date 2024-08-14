<?php

namespace App\Security\Voter;

use App\Repository\TaskRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'task_access' === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ('task_access' === $attribute) {
            $task = $this->taskRepository->find($subject);
        }
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$task) {
            return false;
        }

        return $user->isAdmin() || $task->getEmployees()->contains($user);
    }
}
