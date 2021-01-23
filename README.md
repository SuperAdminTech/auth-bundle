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

    SuperAdmin\Bundle\Security\Authenticator\ApiKeyAuthenticator:
        arguments: [ '@doctrine.orm.entity_manager']

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

```shell
# File .env.test

###> superadmin/auth-bundle?env=test ###
ADMIN_ID=AD779175-76D1-466A-99BF-536AA3F5E001
ADMIN_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDAyMzY1MTMsImV4cCI6NDc1MzgzNjUxMywicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImFkbWluQGV4YW1wbGUuY29tIiwiaXAiOiIxMjcuMC4wLjEiLCJpZCI6IkFENzc5MTc1LTc2RDEtNDY2QS05OUJGLTUzNkFBM0Y1RTAwMSIsImFwcGxpY2F0aW9uIjp7Im5hbWUiOiJEZWZhdWx0IGFwcCIsInJlYWxtIjoiZGVmYXVsdCJ9LCJwZXJtaXNzaW9ucyI6W3siZ3JhbnRzIjpbIkFDQ09VTlRfTUFOQUdFUiJdLCJhY2NvdW50Ijp7ImlkIjoiMDVFODg3MTQtOEZCMy00NkIwLTg5M0QtOTdDQkNBODU5MDAxIn19XX0.YvX5p-9mqguejXQOkuQsPYCd3eCD-69tuzWWmRuH1VYKBTBq9WjTQ49qTdNkWJ050YxnIQ7crVjNvrrur3dcsOCA2UPX0-Vv04QKc25vKKFBJFgL6eF1dhGn8bBXkdbgJ4nnnMRDOmfnaebrFldsk7e_OxX2uf8TqNSuB7wjn9jB8mcy4v3qzwy0mT21EDdwJIATnII0ybO0mVaKdW79G-jiQUnk5kDj0FsmUT5IASS9Qa2GpQrVd_Iv-A2aOwGJ-QTmdVd4boXtNep45vVWX2dXCaTT-gZ8VrLxzDnjjQvd1yZK0TmeUZToHes0JoXefCblsrmeU3bJX4C5mn2Hty_608cXTMilwBnvOosbgE4tbhyAhFuBLkaJh68h4ufhaSTKx0uLT7YR-tf9cbWRAfIwVyFNSNQ0ShOXxUDYAUpPSGI8rFdUN4Hc2nvQpIDwfZdIiYW_MMzB5KUa0FMBl66B6LIs4dPJ5V5ZCr3vwHOaHXpUkzr338UI-cJXEMDcFCkBDLPXbx98k5YgPx9kWC0fPF2XIm09nnTMix51tPI2_dxJJtQZmq6UcQI18Ty1RTrI8nb0vmNf8Ze6N5Yd549jJ3XxBE7WOih20nNfR74egOfar80pzKSLz1P4Knf0_h1dPLnRJwtrbjs57UOiFor8vSgDw9j-GjTGeVISmN0

USER_ID=AD779175-76D1-466A-99BF-536AA3F5E002
USER_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MDAyMzYwNjIsImV4cCI6NDc1MzgzNjA2Miwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdEBleGFtcGxlLmNvbSIsImlwIjoiMTI3LjAuMC4xIiwiaWQiOiJBRDc3OTE3NS03NkQxLTQ2NkEtOTlCRi01MzZBQTNGNUUwMDIiLCJhcHBsaWNhdGlvbiI6eyJuYW1lIjoiRGVmYXVsdCBhcHAiLCJyZWFsbSI6ImRlZmF1bHQifSwicGVybWlzc2lvbnMiOlt7ImdyYW50cyI6WyJBQ0NPVU5UX01BTkFHRVIiLCJBQ0NPVU5UX0lOVkVTVE9SIl0sImFjY291bnQiOnsiaWQiOiIwNUU4ODcxNC04RkIzLTQ2QjAtODkzRC05N0NCQ0E4NTkwMDIifX1dfQ.aGs5bIBqPxSFB3M0jYFHaHGgUhxFMJjrZhVBTGEC9qcxbi_KXzcSzt73DvKzm6XGCQm-GvkGSdxEaYNqCC8SLg1zfsu1jxF_49B_e8pTERI5V-oSB8p5NMitCHq8rbYyLUQktyJnbmIb0Ewv7Igxsi8__ouMVUE_gE1tRiUM59vSf5YRbuiDEwyir94cfEUHinIIu7NS8TcuCNmzGhZhCTCBOw9j9ZX9hCbMPDCCPy9sTmDzjsFdJgnjtR13fQryS28GNRXgNIJWZTeotmjPwQbBqpcqx6PY-vSC85DuTZuahqyAQD-UPXnmNgB496ukccZ1Qf-qLHEmSXDgnZ0A9GC6eggswy-UEDiTrasDr1HJtN-IYnB1eSA43flLMqqezsArxZ-C6G1QCsV5IFKRd9LJ1xadfhAMsp3gjCQdQsm3JL6BkH74fyuO5SatMR7vghNg4crVKZFb05Nn0SxfumInO0qLnmY64R0Jeem5ELiYpuwMfQR1S4f4ObpDxkr1QXuNKdys1GbBBDpgfXJz20ddTb3NB3ZY_u3jtgx1GwjQZhkyrqj9nZYSjZyjWhahTwHsL4QnjZa7wgosY5hkQ7w2gpxmkD60D5-ooFUYCgUlTFiAdPgjfIOuHvbsiPzlGTZ3RFB5I2aM_8zDLC_bXlEBHQw9XiHxF5YVBXKYWTs
###< superadmin/auth-bundle?env=test ###
```


# Development

[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)
