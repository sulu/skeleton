# AGENTS.md

This is a Sulu project. Sulu is a content management system built on Symfony, so
every Symfony convention applies too. Check `composer.json` for the exact Sulu,
Symfony and PHP versions, and read `symfony.lock` to see which recipes ran. Don't
assume packages that aren't listed there, such as API Platform or Messenger.

## Ask before generating

If the task doesn't specify, ask rather than guess:

- Content: which template type (page, article, snippet or another one the project
  has), or a custom entity with its own admin view?
- Output: a Twig page for the website, or an API?
- Webspace: which webspace and which locales does it belong to?

If you can't ask (no interactive channel), state the assumption you're making and
pick the smallest option (e.g. a new page template) rather than scaffolding a
custom entity with admin lists and forms nobody asked for.

## Two contexts, two consoles

Sulu runs an admin context and a website context. Use the console that matches:

- `bin/adminconsole` for Sulu and Doctrine commands (`sulu:admin:info`,
  `sulu:media:init`, `doctrine:*`).
- `bin/websiteconsole` for the website context (`cache:clear`, `cache:warmup`).

The scripts in `composer.json` show which command runs in which context. Follow
them. Tools that need a warm cache, such as PHPStan and Rector, expect
`bin/websiteconsole cache:warmup --env=dev` first.

## Content: templates, not controllers

Pages, articles and snippets are defined by a template. Start there, not with a
new route.

- The template definition is XML in `config/templates/<type>/`. The skeleton ships
  the types `pages`, `articles` and `snippets`, but the types are not limited to
  these. Other bundles and your own code can add more. It sets the `key`, the
  `view`, the `controller`, the `properties` and the cache lifetime.
- The skeleton templates use Sulu's `ContentController`, so they need no PHP. When
  a template needs more data than its properties, such as a list of related
  entries, point `<controller>` to your own controller. Extend the Sulu base
  controller and add the data to the Twig context, instead of fetching it in
  Twig.
- The Twig file named in `<view>` lives in `templates/`, for example
  `templates/pages/default.html.twig`. It extends `base.html.twig` and reads the
  properties from `content`, for example `content.title`.
- Webspaces are XML in `config/webspaces/`. They set the locales, the default
  templates and the navigation contexts.
- Give every property a `<meta>` title for each locale the admin offers, and use
  Sulu's property types (`text_line`, `text_editor`, `route`) before inventing
  custom ones.
- Don't build a separate Symfony route for something a template covers. A route
  is for what is not content, such as a form handler or an API.

Custom entities go in `src/Entity/`. Their admin lists and forms are XML in
`config/lists/` and `config/forms/`, registered through an Admin class.

## Admin JavaScript build

The admin interface is a React app that is built with webpack in `assets/admin/`.
The built files in `public/build/` are generated. Never edit them by hand.

- Run `composer install` first. The npm scripts read from `vendor/sulu/sulu/`.
- Then run `npm install` and `npm run build` in `assets/admin/`. Use
  `npm run watch` while developing.
- Put project JavaScript, such as custom field types or views and imports of
  additional bundles, in `assets/admin/app.js`.
- Don't edit `assets/admin/index.js`. Only `bin/console sulu:admin:update-build`
  changes it. Run that command after you add a bundle with admin JavaScript or
  upgrade Sulu.
- Rebuild after every change in `assets/admin/` and after every Sulu or bundle
  upgrade. Templates, Twig files and PHP code need no rebuild.
- `composer lint-admin-build` runs `sulu:admin:validate-build` and fails when
  `index.js` and the installed bundles are out of sync.

The skeleton has no JavaScript build for the website. Ask before adding one.

## Adding features: Flex, not hand-wiring

Install new capabilities with `composer require <package>` and let the Flex recipe
register the bundle and generate its config. Don't hand-edit `config/bundles.php`
or hand-write a bundle's base config. Look for an official Sulu bundle at
https://github.com/sulu before writing your own.

## Conventions

Follow https://symfony.com/doc/current/best_practices.html to write idiomatic
Symfony:

- Use PHP attributes for framework metadata: `#[Route]`, `#[MapRequestPayload]`,
  `#[IsGranted]`, `#[Assert\...]`, `#[AsCommand]`, `#[AsEventListener]`,
  `#[AsMessageHandler]`, and `#[Autowire]` / `#[Target]` on services.
- Rely on autowiring and autoconfiguration. A YAML service definition is the last
  resort.
- Controllers extend `AbstractController`, stay thin, and delegate to services.
- Use the framework for what it already does: Validator for validation,
  Serializer for JSON, Messenger for async work, Lock for mutual exclusion,
  Security voters for access control, Twig `path()`/`url()` instead of hardcoded
  URLs.
- Use constructor property promotion, and `readonly` for DTOs and value objects.
- Don't change the `SuluKernel` setup in `src/Kernel.php` unless the task needs it.

## Everyday workflow

- Start the database with `docker compose up -d`. It runs MySQL as set in
  `compose.yaml`, and `DATABASE_URL` in `.env` points to it.
- Run the app with `composer serve`, or `symfony serve -d` if the Symfony CLI is
  installed.
- When something fails, read `var/log/dev.log` and the web profiler
  (`/_profiler`) before changing code.
- Schema changes go through migrations in `migrations/`, never
  `doctrine:schema:update` or hand-written SQL.
- Admin translations are XLIFF files in `translations/`. Add a key for every
  locale you use.
- `.env` is committed and holds defaults only. Real secrets belong in `.env.local`
  (git-ignored) or the secrets vault (`bin/console secrets:set`), read via
  `%env(...)%`.
- Cached pages are served by the HTTP cache. After changing a template, clear the
  cache before judging the result.

## Testing

Create the test database with `composer bootstrap-test-environment`, then run
`composer test`. A feature isn't done until it has a test that exercises it the way
a caller would, an HTTP request for a controller or a service call for a service,
not just "it didn't throw."

## Code style

Run `composer lint` before you finish. It runs PHPStan, PHP-CS-Fixer, Rector,
Twig-CS-Fixer, the Twig, YAML and XLIFF linters, the admin build check, the
container lint for every environment and the Doctrine schema check. Run
`composer fix` to apply the automatic fixes.

## Discover, don't guess

Sulu and Symfony APIs change between versions and your training data may be
stale. Look things up in the project instead of relying on memory:

- `bin/adminconsole sulu:admin:info`: versions and paths of the admin.
- `bin/adminconsole debug:router`, `debug:container`, `debug:autowiring <name>`,
  `debug:config <bundle>`, `config:dump-reference <bundle>`: what exists and how
  it is configured.
- The XSD files named in the XML templates and webspaces list every valid element.
- Read the installed source and docblocks under `vendor/sulu/`.
- Docs: https://docs.sulu.io/ (switch to the version matching `composer.json`).
