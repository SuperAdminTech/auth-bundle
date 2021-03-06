<?php

namespace SuperAdmin\Bundle\Entity\Compose;

use SuperAdmin\Bundle\Security\Account;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AccountOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait AccountOwnedTrait {

    /**
     * @var string
     * @ORM\Column(type="guid")
     * @Assert\Uuid()
     * @Groups({"user:read", "user:write"})
     */
    public $account_id;

    public function getAccount(): ?Account {
        if ($this->account_id === null) return null;
        return Account::createFromId($this->account_id);
    }

    public function setAccount(Account $account = null) {
        $this->account_id = $account->id;
    }
}