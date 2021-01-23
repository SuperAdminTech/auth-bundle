<?php


namespace SuperAdmin\Bundle\Security;

/**
 * Interface UserOwned
 * @package SuperAdmin\Bundle\Security
 */
interface UserOwned {

    /**
     * @return User
     */
    function getUser(): ?User;
}