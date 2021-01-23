<?php

namespace SuperAdmin\Bundle\Security\Voter;

use SuperAdmin\Bundle\Security\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class ApiKeyUrlsVoter
 * @package SuperAdmin\Bundle\Security\Voter
 */
class ApiKeyUrlsVoter extends Voter {

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
        $supportsAttribute = $attribute !== 'IS_AUTHENTICATED_ANONYMOUSLY';
        $supportsSubject = $subject instanceof Request;
        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        foreach ($user->urls as $url){
            $pattern = '#^' . $url . '$#i';
            if(preg_match($pattern, $subject->getRequestUri()))
                return true;
        }
        return false;
    }
}