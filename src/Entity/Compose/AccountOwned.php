<?php


namespace SuperAdmin\Entity\Compose;

use SuperAdmin\Security\Account;

/**
 * Interface AccountOwned
 * @package SuperAdmin\Entity\Compose
 */
interface AccountOwned {

    /**
     * @return Account
     */
    function getAccount(): Account;
}