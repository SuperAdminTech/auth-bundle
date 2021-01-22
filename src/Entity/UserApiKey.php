<?php

namespace SuperAdmin\Entity;

use SuperAdmin\Entity\Compose\UserOwnedTrait;
use SuperAdmin\Security\UserOwned;

/**
 * Class UserApiKey
 * @package SuperAdmin\Entity
 */
class UserApiKey extends ApiKey implements UserOwned {
    use UserOwnedTrait;
}