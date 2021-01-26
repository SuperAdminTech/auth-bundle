<?php


namespace SuperAdmin\Bundle\Security;


/**
 * Interface AccountOwned
 * @package SuperAdmin\Bundle\Security
 */
interface AccountOwned {
    function getAccount(): ?Account;
    function setAccount(Account $account = null);
}