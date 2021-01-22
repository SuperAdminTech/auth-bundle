<?php


namespace SuperAdmin\Security;

/**
 * Interface UserOwned
 * @package SuperAdmin\Security
 */
interface UserOwned {

    /**
     * @return User
     */
    function getUser(): User;
}