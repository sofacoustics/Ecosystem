# SONICOM Ecosystem

Web platform for publishing acoustic databases (SOFA, HRTFs, SRIRs, etc.) and tools, with DOIs assigned via the RADAR (datathek) API.

- Code: https://github.com/sofacoustics/Ecosystem
- Issues: https://github.com/sofacoustics/Ecosystem/issues — reference issue URLs in commit messages when fixing a ticket.
- Feature history / metadata status: `history.txt` (outdated currently)

## Repository layout

- `laravel/` — the web app (Laravel 12, Livewire 3, Reverb, Sanctum, spatie/laravel-permission, spatie/laravel-data, Tailwind + Vite).
- `octave/` — scripts to build a custom Octave 10.2 (with GL2PS + PNG support) used by the services; see `octave/README.md`.
- `git-hooks/` — post-checkout/post-merge automation for the servers (see Deployment).
- `scripts/` — server helper scripts.

## Domain model

- **Database** → has **Datasetdefs** (dataset definitions: which datafiles a dataset contains, each with a **Datafiletype**) → has **Datasets** → each with **Datafiles**.
- **Tool** — standalone software/model/text resource, also publishable with a DOI.
- **Metadata** (Creator, Publisher, Rightsholder, Keyword, SubjectArea, RelatedIdentifier) is attached to Databases and Tools via polymorphic one-to-many relations.
- **Widgets** display a datafile (image, audio, SOFA properties, STL viewer, CSV table…); **Services** process a datafile (e.g. Octave scripts that generate figures). Which widgets/services apply is defined per Datafiletype.
- Service scripts live in `laravel/storage/app/services/<id>/` (e.g. `1/HRTFGeneral.m`) and are run by the queued job `app/Jobs/Service.php` as external processes.
- RADAR integration: `app/Services/*RadarBridge.php` (mapping to RADAR datasets/folders/files), `app/Jobs/DatabasePublishToRadar.php`, `app/Jobs/DatabasePollForPublicationDate.php`, `app/Livewire/*RadarActions.php`. Config in `config/services.php` (`radar` key).

## Seeders

- `database/seeders/Static/` — reference data needed in production (Datafiletypes, Widgets, Services, DatafiletypeWidget, Metadataschema, Roles/Permissions, MenuItems). Run via `StaticSeeder`.
- `database/seeders/Test/` — fake data for development only. Never run against the live database.
- Adding a new widget or service usually requires updating `ServiceSeeder`, `WidgetSeeder` and `DatafiletypeWidgetSeeder` consistently.
- Static seeders are re-run automatically on deployment, so they must be idempotent (update-or-create, no duplicates).

## Development & testing workflow

- There is no local runtime. Changes are copied to a **dev server** and tested there in the browser. Claude cannot run the app, so state clearly what was changed and what should be tested manually.
- The dev server is connected to a **RADAR test endpoint**; the live server to production RADAR. DOI assignment/publishing on production cannot be undone.
- `laravel/tests/` contains only the default Breeze tests; there is no meaningful automated test suite.
- Main branch: `dev`.

## Deployment

`git-hooks/laravel-automation.sh` runs on checkout/merge on the servers and automatically:
- runs `composer install`, `npm ci && npm run build`
- runs `php artisan migrate --force` if migrations changed
- runs `db:seed --class StaticSeeder` if static seeders changed
- recaches config/routes/views/events if those changed
- restarts queues via `supervisorctl`

Consequences: migrations must be safe to run on live data (no destructive changes without explicit agreement), and config changes require a config recache.

## Rules

- **Never read, print, modify or commit `.env*` files** (`.env`, `.env.dev`, `.env.live`, backups). They contain credentials. Only `.env.example` may be edited.
- Do not run `migrate:fresh`, `migrate:reset`, `db:wipe` or test seeders unless explicitly asked.
- Do not call the RADAR API or trigger DOI actions.
- Dataset names and dataset definition names must be unique within a database and must not contain `<>:&"\|?*/` (they are used as file/folder names in downloads and RADAR uploads).
- Metadata fields follow the RADAR schema; check `history.txt` ("Metadata status") before adding or changing metadata.
- Production code runs on Linux; the development machine is Windows. Use forward slashes in paths and keep LF line endings in shell scripts.
