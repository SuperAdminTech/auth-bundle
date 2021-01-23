<?php

namespace SuperAdmin\Bundle\Security\Voter;

use SuperAdmin\Bundle\Security\AccountOwned;
use SuperAdmin\Bundle\Security\User;
use SuperAdmin\Bundle\Security\UserOwned;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserGrantsVoter
 * @package SuperAdmin\Bundle\Security\Voter
 */
class UserGrantsVoter extends Voter {

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
        return $subject instanceof UserOwned;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $subject->user_id === $user->id;
    }
}