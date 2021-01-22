<?php


namespace SuperAdmin\Security;


/**
 * Interface AccountOwned
 * @package SuperAdmin\Security
 */
interface AccountOwned {

    /**
     * @return Account
     */
    function getAccount(): Account;
}