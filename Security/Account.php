<?php

namespace SuperAdmin\Security;

class Account implements Owner {

    /** @var string */
    public string $id;

    /**
     * @param string $id
     * @return Account
     */
    public static function createFromId(string $id): Account {
        $account = new Account();
        $account->id = $id;
        return $account;
    }

    function getId()
    {
        return $this->id;
    }
}