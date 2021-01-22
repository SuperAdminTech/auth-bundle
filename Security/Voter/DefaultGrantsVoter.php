<?php

namespace SuperAdmin\Security\Voter;

use SuperAdmin\Entity\Compose\Owned;
use SuperAdmin\Security\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class DefaultGrantsVoter
 * @package SuperAdmin\Security\Voter
 */
class DefaultGrantsVoter extends Voter {

    /** @var Security */
    private $security;

    /**
     * RunnerVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        $supportedGrants = [
            User::ACCOUNT_MANAGER,
            User::ACCOUNT_WORKER
        ];
        $supportsAttribute = in_array($attribute, $supportedGrants);
        $supportsSubject = $subject instanceof Owned;

        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        foreach ($user->permissions as $permission){
            $isManager = in_array(User::ACCOUNT_MANAGER, $permission['grants']);
            $isWorker = in_array(User::ACCOUNT_WORKER, $permission['grants']);
            if($subject->owner_id == $permission['account']['id']) {
                if ($isManager) return true;
                if ($isWorker && $attribute === User::ACCOUNT_WORKER) return true;
            }
        }
        return false;
    }
}