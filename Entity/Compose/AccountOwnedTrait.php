<?php

namespace SuperAdmin\Bundle\Entity\Compose;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait AccountOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait AccountOwnedTrait {

    /**
     * @var string
     * @ORM\Column(type="guid")
     * @Groups({"user:read", "user:write"})
     */
    public $account_id;

}