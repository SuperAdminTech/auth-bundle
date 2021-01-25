<?php

namespace SuperAdmin\Bundle\EventSubscriber\Doctrine;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use SuperAdmin\Bundle\Security\User;
use SuperAdmin\Bundle\Security\UserOwned;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class SetUserToOwnedSubscriber implements EventSubscriber
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * AddUserToOwnedSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {

        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $owned = $event->getEntity();

        if (!$owned instanceof UserOwned) {
            return;
        }

        //if user_id is already set, skip
        if($owned->getUser() != null){
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $owner = $token->getUser();
        if (!$owner instanceof User) {
            return;
        }

        $owned->setUser($owner);
    }
}
