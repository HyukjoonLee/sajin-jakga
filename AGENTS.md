# AGENTS.md

## Project Overview

This project is a Rhymix-based bulletin board/community site for photographers.
It runs locally with Docker Compose and uses Rhymix as the CMS foundation.

## Runtime

- PHP app: `php:8.2-fpm`
- Web server: Nginx
- Database: MySQL 8.0
- Cache: Redis 7.0
- Main entrypoint: `index.php`
- Runtime config: `files/config/config.php`

## Git Rules

Commit source files and templates only.

Do commit:

- `Dockerfile`
- `docker-compose.yml`
- `entrypoint.sh`
- `nginx/nginx.conf`
- `php.ini`
- `.env.example`
- `.dockerignore`
- `AGENTS.md`
- `GEMINI.md`
- `scripts/setup-config.php`
- `README.md`
- project-specific Rhymix layouts, modules, addons, widgets, and skins

Do not commit:

- `.env`
- `.env.*` except `.env.example`
- `files/`
- `files/config/config.php`
- generated cache files
- uploaded files
- `.DS_Store`
- log files

## Rhymix Config

`files/config/config.php` is generated at container startup by:

```sh
php /var/www/html/scripts/setup-config.php
```

This generated file contains DB credentials and crypto keys. Never commit it.

`files/` is intentionally ignored. A fresh clone should not contain it. The setup script creates `files/config/` and `files/config/config.php` when the app container starts.

If `.env` changes during early development, regenerate the config with caution:

```sh
rm files/config/config.php
docker compose restart app
```

Do not recommend this for production because crypto keys may be regenerated.

Expected Redis cache server format:

```php
'servers' => [
    'redis://redis:6379',
],
```

## Docker

Useful verification commands:

```sh
docker compose config
docker compose build app
docker compose up -d
```

Nginx, MySQL, and Redis ports should remain bound to `127.0.0.1` for local development.

The app must wait for both MySQL and Redis healthchecks before starting.

Docker build context must exclude `.env` and `files/`.

## Required PHP Extensions

The PHP image should include:

- `gd`
- `pdo_mysql`
- `zip`
- `curl`
- `mbstring`
- `redis`

Prefer pinned PECL extension versions when possible.

## Nginx

Sensitive paths should be blocked, including:

- dotfiles
- `.env`
- `files/config`
- `scripts`
- Docker files
- config files

For local development, published ports should bind to `127.0.0.1`.

## Editing Guidelines

Prefer small, targeted changes.

Do not modify Rhymix core files unless the change is intentional and documented. Place project-specific customization in layouts, modules, addons, widgets, skins, or config scripts when possible.

Do not commit generated runtime state.

## Code Review Guidelines

When asked to review changes, use a bug- and risk-focused review style.

Prioritize findings in this order:

1. Security risks
2. Data loss or config regeneration risks
3. Docker/runtime breakage
4. Rhymix compatibility issues
5. Git hygiene and generated files
6. Documentation drift
7. Maintainability

For each finding, include:

- severity: `High`, `Medium`, or `Low`
- file path and line number
- concrete impact
- recommended fix

Avoid broad style comments unless they affect correctness, security, or future maintenance.

## Review Checklist

Check that these are not committed:

- `.env`
- `.env.*` except `.env.example`
- `files/`
- `files/config/config.php`
- uploaded files
- cache files
- `.DS_Store`
- log files

Check that these are committed when relevant:

- `.env.example`
- `.dockerignore`
- `scripts/setup-config.php`
- `AGENTS.md`
- `GEMINI.md`
- Docker, Nginx, and PHP config files

When reviewing Docker changes, verify:

- `docker compose config` passes
- MySQL and Redis are bound to `127.0.0.1`
- app waits for MySQL healthcheck
- app waits for Redis healthcheck if Redis cache is enabled
- Docker build context excludes `.env` and `files/`
- volumes do not hide required files unexpectedly

When reviewing README changes, verify:

- setup commands work from a fresh clone
- `.env.example` values match documented ports and credentials
- generated files are documented
- warnings exist for config regeneration and crypto key reset
- Git-managed vs ignored files are clear

## Review Output Format

Use this structure:

```md
**Findings**

- **High** `path/to/file:line`: issue and impact. Recommended fix.
- **Medium** `path/to/file:line`: issue and impact. Recommended fix.
- **Low** `path/to/file:line`: issue and impact. Recommended fix.

**Open Questions**

- Any assumptions or unclear intent.

**Verification**

- Commands run and results.
- Commands not run and why.
```

If there are no findings, say that clearly and mention residual risks or test gaps.
