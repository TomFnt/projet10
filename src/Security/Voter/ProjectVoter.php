<?php

namespace App\Security\Voter;

use App\Repository\ProjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'project_access' === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ('project_access' === $attribute) {
            $project = $this->projectRepository->find($subject);
        }
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$project) {
            return false;
        }

        return $user->isAdmin() || $project->getEmployees()->contains($user);
    }
}
