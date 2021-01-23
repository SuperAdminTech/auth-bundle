<?php

namespace SuperAdmin\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class ApiKey
 * @package SuperAdmin\Bundle\Entity
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