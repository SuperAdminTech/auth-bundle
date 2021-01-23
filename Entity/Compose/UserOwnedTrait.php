<?php


namespace SuperAdmin\Bundle\Entity\Compose;


use SuperAdmin\Bundle\Security\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UserOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait UserOwnedTrait
{

    /**
     * @var string
     * @ORM\Column(type="guid")
     */
    public $user_id;

    /**
     * @return User
     */
    public function getUser(): User {
        return User::createFromId($this->user_id);
    }
}