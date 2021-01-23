<?php


namespace SuperAdmin\Bundle\Entity\Compose;


use SuperAdmin\Bundle\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait UserOwnedTrait
 * @package SuperAdmin\Bundle\Entity\Compose
 */
trait UserOwnedTrait
{

    /**
     * @var string
     * @ORM\Column(type="guid")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $user_id;

    /**
     * @return User
     */
    public function getUser(): User {
        return User::createFromId($this->user_id);
    }
}