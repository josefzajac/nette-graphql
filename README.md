
Main goal is to provide best prepared starter-kit project for Nette developers.

Focused on:

- PHP 8.1+
- `nette/*` packages
- Doctrine ORM via `nettrine/*`
- Symfony components via `contributte/*`
- codestyle checking via **CodeSniffer** and `contributte/qa`
- static analysing via **phpstan** and `contributte/phpstan`
- unit / integration tests via **Nette Tester** and `contributte/tester`





1) Run `docker-compose up`

2) Open http://localhost and enjoy!

   Take a look at:
    - http://localhost:8081.
    - http://localhost:8081/admin (admin@admin.cz / admin)

## Features

Here is a list of all features you can find in this project.

- PHP 8.0+
- :package: Packages
    - Nette 3+
    - Contributte
    - Nettrine
- :deciduous_tree: Structure
    - `app`
        - `config` - configuration files
            - `env` - prod/dev/test environments
            - `app` - application configs
            - `ext` - extensions configs
            - `local.neon` - local runtime config
            - `local.neon.dist` - template for local config
        - `domain` - business logic and domain specific classes
        - `model` - application backbone
        - `modules` - Front/Admin module, presenters and components
        - `resources` - static content for mails and others
        - `ui` - UI components and base classes
        - `bootstrap.php` - Nette entrypoint
    - `bin` - console entrypoint (`bin/console`)
    - `db` - database files
        - `fixtures` - PHP fixtures
        - `migrations` - migrations files
    - `docs` - documentation
    - `var`
        - `log` - runtime and error logs
        - `tmp` - tmp files and cache
    - `tests` - test engine and unit/integration tests
    - `vendor` - composer's folder
    - `www` - public content
- :exclamation: Tracy
    - Cool error 500 page

### Notable changes

- `$user` variable in templates [is renamed](https://github.com/contributte/webapp-skeleton/blob/master/app/model/Latte/TemplateFactory.php) to `$_user`

### Composer packages

Take a detailed look :eyes: at each single package.

- [contributte/bootstrap](https://contributte.org/packages/contributte/bootstrap.html)
- [contributte/application](https://contributte.org/packages/contributte/application.html)
- [contributte/di](https://contributte.org/packages/contributte/di.html)
- [contributte/cache](https://contributte.org/packages/contributte/cache.html)
- [contributte/http](https://contributte.org/packages/contributte/http.html)
- [contributte/forms](https://contributte.org/packages/contributte/forms.html)
- [contributte/latte](https://contributte.org/packages/contributte/latte.html)
- [contributte/mail](https://contributte.org/packages/contributte/mail.html)
- [contributte/security](https://contributte.org/packages/contributte/security.html)
- [contributte/utils](https://contributte.org/packages/contributte/utils.html)
- [contributte/tracy](https://contributte.org/packages/contributte/tracy.html)
- [contributte/console](https://contributte.org/packages/contributte/console.html)
- [contributte/webapp-skeleton](https://contributte.org/packages/contributte/webapp-skeleton.html)
- [contributte/event-dispatcher](https://contributte.org/packages/contributte/event-dispatcher.html)
- [contributte/event-dispatcher-extra](https://contributte.org/packages/contributte/event-dispatcher-extra.html)
- [contributte/neonizer](https://contributte.org/packages/contributte/neonizer.html)
- [contributte/mailing](https://contributte.org/packages/contributte/mailing.html)
- [contributte/monolog](https://contributte.org/packages/contributte/monolog.html)

**Doctrine**

- [contributte/doctrine-orm](https://contributte.org/packages/contributte/doctrine-orm.html)
- [contributte/doctrine-dbal](https://contributte.org/packages/contributte/doctrine-dbal.html)
- [contributte/doctrine-annotations](https://contributte.org/packages/contributte/doctrine-annotations.html)
- [contributte/doctrine-cache](https://contributte.org/packages/contributte/doctrine-cache.html)
- [contributte/doctrine-migrations](https://contributte.org/packages/contributte/doctrine-migrations.html)
- [contributte/doctrine-fixtures](https://contributte.org/packages/contributte/doctrine-fixtures.html)

**Dev**

- [contributte/dev](https://contributte.org/packages/contributte/dev.html)
- [ninjify/qa](https://contributte.org/packages/ninjify/qa.html)
- [ninjify/nunjuck](https://contributte.org/packages/ninjify/nunjuck.html)
- [phpstan/phpstan](https://github.com/phpstan/phpstan)
- [mockery/mockery](https://github.com/mockery/mockery)
- [nelmio/alice](https://github.com/nelmio/alice)

## Screenshots

![](.docs/assets/screenshot1.png)

> admin@admin.cz / admin

![](.docs/assets/screenshot2.png)
![](.docs/assets/screenshot3.png)
![](.docs/assets/screenshot4.png)

## Development

See [how to contribute](https://contributte.org/contributing.html) to this package.

This package is currently maintaining by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team. Also thank you for using this project.
