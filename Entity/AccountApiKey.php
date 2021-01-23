<?php

namespace SuperAdmin\Bundle\Entity;

use SuperAdmin\Bundle\Entity\Compose\AccountOwnedTrait;
use SuperAdmin\Bundle\Security\AccountOwned;

/**
 * Class AccountApiKey
 * @package SuperAdmin\Bundle\Entity
 */
class AccountApiKey extends ApiKey implements AccountOwned {
    use AccountOwnedTrait;
}