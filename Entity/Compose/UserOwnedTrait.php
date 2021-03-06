<?php


namespace SuperAdmin\Bundle\Entity\Compose;


use SuperAdmin\Bundle\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Assert\Uuid()
     * @Groups({"user:read", "user:write"})
     */
    public $user_id;

    public function getUser(): ?User {
        if ($this->user_id === null) return null;
        return User::createFromId($this->user_id);
    }

    public function setUser(User $user = null) {
        $this->user_id = $user->id;
    }
}