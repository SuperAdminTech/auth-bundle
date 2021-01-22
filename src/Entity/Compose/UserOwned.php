<?php


namespace SuperAdmin\Entity\Compose;

use SuperAdmin\Security\User;

/**
 * Interface UserOwned
 * @package SuperAdmin\Entity\Compose
 */
interface UserOwned {

    /**
     * @return User
     */
    function getUser(): User;
}