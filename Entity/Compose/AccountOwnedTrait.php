<?php

namespace SuperAdmin\Bundle\Entity\Compose;

use SuperAdmin\Bundle\Security\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AccountOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait AccountOwnedTrait {

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    public $account_id;

    /**
     * @return Account
     */
    public function getAccount(): Account {
        return Account::createFromId($this->account_id);
    }

}