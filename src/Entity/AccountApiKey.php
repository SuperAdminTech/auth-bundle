<?php

namespace SuperAdmin\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use SuperAdmin\Entity\Compose\AccountOwned;
use SuperAdmin\Entity\Compose\AccountOwnedTrait;
use SuperAdmin\Security\Account;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AccountApiKey
 * @package SuperAdmin\Entity
 */
class AccountApiKey extends ApiKey implements AccountOwned {
    use AccountOwnedTrait;
}