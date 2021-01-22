<?php

namespace SuperAdmin\Entity;

use SuperAdmin\Entity\Compose\AccountOwnedTrait;
use SuperAdmin\Security\AccountOwned;

/**
 * Class AccountApiKey
 * @package SuperAdmin\Entity
 */
class AccountApiKey extends ApiKey implements AccountOwned {
    use AccountOwnedTrait;
}