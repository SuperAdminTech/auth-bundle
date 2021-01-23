SuperAdmin AuthBundle
=====================

This package provides esay [Symfony](https://symfony.com) integration with the [SuperAdmin](https://superadmin.org) API.

You can either start your own [SuperAdmin Server]() or use the managed service for free.

# Installation
As of version `~1.0` of this bundle, there is only provided the implementation classes,
the configuration and dependency management must be done by the user (documented here).
In further versions it will go automatically at bundle installation.

## Dependencies

`composer require lexik/jwt-authentication-bundle`

## Install via composer

`composer require superadmin/auth-bundle ~1.0`

## Configuration

After installation, you must add the following settings to your config files

```yaml
# File config/packages/security.yaml

security:
    
    access_decision_manager:
        strategy: unanimous
        allow_if_all_abstain: false
    
    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        jwt:
            lexik_jwt:
                class: SuperAdmin\Bundle\Security\User

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            pattern: ^/
            stateless: true
            anonymous: true
            provider: jwt
            guard:
                authenticators:
                    - lexik_jwt_authentication.jwt_token_authenticator
                    - SuperAdmin\Bundle\Security\Authenticator\ApiKeyAuthenticator
                entry_point: lexik_jwt_authentication.jwt_token_authenticator

            # activate different ways to authenticate
            # https://symfony.com/doc/current/security.html#firewalls-authentication

            # https://symfony.com/doc/current/security/impersonating_user.html
            # switch_user: true

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/docs, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/public, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/sadmin, roles: ROLE_SUPER_ADMIN }
        - { path: ^/admin, roles: ROLE_ADMIN }
        - { path: ^/, roles: ROLE_USER }
```

```yaml
# File config/services.yaml

services:
    # here your original configurations
    # ...
    
    SuperAdmin\Bundle\Security\Serializer\ContextBuilder:
        decorates: 'api_platform.serializer.context_builder'
        arguments: [ '@SuperAdmin\Bundle\Security\Serializer\ContextBuilder.inner' ]
        autoconfigure: false

    SuperAdmin\Bundle\EventListener\OwnedFilterConfigurator:
        tags:
            - { name: kernel.event_listener, event: kernel.request, priority: 5 }
        # Autoconfiguration must be disabled to set a custom priority
        autoconfigure: false

```

# Development

[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)
