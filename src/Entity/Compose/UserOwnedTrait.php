<?php


namespace SuperAdmin\Entity\Compose;


use SuperAdmin\Security\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UserOwnedTrait
 * @package SuperAdmin\Entity\Compose
 */
trait UserOwnedTrait
{

    /**
     * @var string
     * @ORM\Column(type="UUID")
     */
    public $user_id;

    /**
     * @return User
     */
    public function getUser(): User {
        return User::createFromId($this->user_id);
    }
}