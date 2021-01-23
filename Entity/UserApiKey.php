<?php

namespace SuperAdmin\Bundle\Entity;

use SuperAdmin\Bundle\Entity\Compose\UserOwnedTrait;
use SuperAdmin\Bundle\Security\UserOwned;

/**
 * Class UserApiKey
 * @package SuperAdmin\Bundle\Entity
 */
class UserApiKey extends ApiKey implements UserOwned {
    use UserOwnedTrait;
}