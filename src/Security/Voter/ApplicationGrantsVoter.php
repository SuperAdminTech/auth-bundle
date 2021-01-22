<?php

namespace SuperAdmin\Security\Voter;

use SuperAdmin\Entity\Compose\Owned;
use SuperAdmin\Security\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class ApplicationGrantsVoter
 * @package SuperAdmin\Security\Voter
 */
class ApplicationGrantsVoter extends Voter {

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
            User::ACCOUNT_INVESTOR,
            User::ACCOUNT_TRADER
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
            if($subject->owner_id == $permission['account']['id'] && in_array($attribute, $permission['grants'])) {
                return true;
            }
        }
        return false;
    }
}