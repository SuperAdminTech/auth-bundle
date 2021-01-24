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

    /**
     * @param Account|null $account
     */
    function setAccount(Account $account = null);
}