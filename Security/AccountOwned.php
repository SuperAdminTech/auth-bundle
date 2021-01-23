<?php


namespace SuperAdmin\Bundle\Security;


/**
 * Interface AccountOwned
 * @package SuperAdmin\Bundle\Security
 */
interface AccountOwned {

    /**
     * @return Account
     */
    function getAccount(): ?Account;
}