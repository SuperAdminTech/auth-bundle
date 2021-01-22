<?php

namespace SuperAdmin\Entity\Compose;

use SuperAdmin\Security\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AccountOwnedTrait
 * @package SuperAdmin\Entity\Compose
 */
trait AccountOwnedTrait {

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    public $user_id;

    /**
     * @return Account
     */
    public function getAccount(): Account {
        return Account::createFromId($this->account_id);
    }

}