<?php


namespace SuperAdmin\Bundle\Security;

/**
 * Interface UserOwned
 * @package SuperAdmin\Bundle\Security
 */
interface UserOwned {
    function getUser(): ?User;
    function setUser(User $user = null);
}