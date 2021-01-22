<?php

namespace SuperAdmin\Entity;

use SuperAdmin\Entity\Compose\UserOwned;
use SuperAdmin\Entity\Compose\UserOwnedTrait;

/**
 * Class UserApiKey
 * @package SuperAdmin\Entity
 */
class UserApiKey extends ApiKey implements UserOwned {
    use UserOwnedTrait;
}