<?php

namespace SuperAdmin\Bundle\Security;

use SuperAdmin\Bundle\Entity\ApiKey;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class User implements JWTUserInterface, Owner {

    public const ACCOUNT_MANAGER = 'ACCOUNT_MANAGER';
    public const ACCOUNT_WORKER = 'ACCOUNT_WORKER';

    /** @var string */
    public string $id;

    /** @var string */
    public string $username;

    /** @var string[] */
    public array $roles;

    /** @var array */
    public array $permissions;

    /** @var array */
    public array $application;

    /** @var array */
    public array $urls;

    /**
     * @return string
     */
    public function __toString(): string {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public static function createFromPayload($username, array $payload)
    {
        $user = new User();

        $user->username = $username;
        $user->id = $payload['id'];
        $user->roles = (array) $payload['roles'];
        $user->permissions = (array) $payload['permissions'];
        $user->application = (array) $payload['application'];
        $user->urls = $payload['urls']?? [ApiKey::URLS_ALL];

        return $user;
    }

    /**
     * @param string $id
     * @return User
     */
    public static function createFromId(string $id): User {
        $user = new User();
        $user->id = $id;
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return (array) $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(){ }

    /**
     * @inheritDoc
     */
    public function getSalt(){ }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(){ }

    function getId() {
        return $this->id;
    }
}