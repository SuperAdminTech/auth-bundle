<?php

namespace SuperAdmin\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use SuperAdmin\Entity\Compose\AccountOwned;
use SuperAdmin\Entity\Compose\NameTrait;
use SuperAdmin\Entity\Compose\Owned;
use SuperAdmin\Entity\Compose\UserOwned;
use SuperAdmin\Entity\Compose\UserOwnedTrait;
use SuperAdmin\Security\Account;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ApiKey
 * @package SuperAdmin\Entity
 */
abstract class ApiKey {

    public const CIDR_ALL = '0.0.0.0/0';
    public const URLS_ALL = '.*';

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
     */
    public $secret;

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"user:read", "user:write"})
     */
    public $authorizations = [];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    public $cidr = [ApiKey::CIDR_ALL];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    public $urls = [ApiKey::URLS_ALL];

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Groups({"user:read"})
     */
    public $read_only = true;

}