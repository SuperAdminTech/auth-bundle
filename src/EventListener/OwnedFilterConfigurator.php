<?php


namespace SuperAdmin\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnedFilterConfigurator
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * OwnedFilterConfigurator constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface  $authorizationChecker)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onKernelRequest(): void
    {
        $user = $this->getUser();

        // only filter when user is authenticated and not admin (admins listings are not filtered)
        if(!$user) return;
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) return;

        $filter = $this->em->getFilters()->enable('owned_filter');
        $filter->setParameter('permissions', base64_encode(json_encode($user->permissions)));
    }

    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        return $user instanceof UserInterface ? $user : null;
    }
}