<?php

namespace SuperAdmin\Bundle\Security\Voter;

use SuperAdmin\Bundle\Security\AccountOwned;
use SuperAdmin\Bundle\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class AccountApplicationGrantsVoter
 * @package SuperAdmin\Bundle\Security\Voter
 */
class AccountGrantsVoter extends Voter {

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
        $supportsAttribute = str_starts_with($attribute, 'ACCOUNT_');
        $supportsSubject = ($subject instanceof AccountOwned) || (is_array($subject) && isset($subject['account_id']));

        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        foreach ($user->permissions as $permission){
            $account_id = $subject['account_id']?? $subject->account_id;
            if($account_id == $permission['account']['id'] && in_array($attribute, $permission['grants'])) {
                return true;
            }
        }
        return false;
    }
}