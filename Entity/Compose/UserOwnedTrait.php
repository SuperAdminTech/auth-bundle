<?php


namespace SuperAdmin\Bundle\Entity\Compose;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait UserOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait UserOwnedTrait
{

    /**
     * @var string
     * @ORM\Column(type="guid")
     * @Groups({"user:read", "user:write"})
     */
    public $user_id;

}