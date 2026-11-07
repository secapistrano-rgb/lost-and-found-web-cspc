# CSPC Lost and Found

A campus lost and found management application for Camarines Sur Polytechnic Colleges (CSPC). It gives administrators and staff a central place to manage reported items, review ownership claims, track matches, and communicate with users.

The web application uses PHP and CodeIgniter with a MySQL database. It also includes API routes for authentication, user management, and selected support features.

## Features

| Module | Capabilities |
| --- | --- |
| Dashboard | Overview of items, claims, and system activity |
| Items | Create, view, edit, and remove lost and found records with images |
| Claims | Review ownership claims, supporting evidence, and claim status |
| Matches | Review potential matches, approve or reject them, and resolve cases |
| Users and roles | Manage user accounts, suspensions, and administrative roles |
| Email and notifications | Compose messages, manage templates, review delivery logs, and track replies |
| Reports | View item and claim reports, analytics, and export results |
| Activity logs | Review recorded actions for auditing and troubleshooting |
| Feedback and support | Respond to feedback, manage tickets, and maintain FAQs and announcements |

Administrative roles include `super_admin`, `admin`, and `staff`. Access is controlled by the application's authentication and role filters.

## Technology

- **Backend:** PHP, CodeIgniter 4.6.1, MySQL through MySQLi.
- **Interface:** PHP views, JavaScript, Bootstrap 5, Tailwind CSS, and custom styles. Some assets load from CDNs.
- **Integrations:** reCAPTCHA, SMTP email, and Gmail IMAP reply polling.
- **Libraries:** Dompdf for PDF generation, Firebase PHP-JWT, and Google API Client.
- **Testing:** PHPUnit, configured through `phpunit.xml.dist`.

## Requirements

- PHP compatible with the `^8.1` constraint in `composer.json` and installed dependencies.
- Composer.
- MySQL; the supplied database snapshot was exported from MySQL 8.0.
- PHP extensions `intl`, `mbstring`, and `mysqli`, plus extensions required by Composer packages.
- PHP IMAP support if using Gmail reply polling.
- Internet access for CDN assets, reCAPTCHA, and configured email services.

## Run locally

Run the following commands from the project root.

### 1. Install dependencies

```sh
composer install
```

CodeIgniter is bundled in `system/`. Keep this folder: the application's path configuration loads the framework from it.

### 2. Configure the environment

Create a `.env` file in the project root. There is no environment template included in this copy.

```dotenv
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = lost_and_found
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi
database.default.port = 3306

RECAPTCHA_SITE_KEY = your_site_key
RECAPTCHA_SECRET_KEY = your_secret_key
```

Use reCAPTCHA keys configured for your local hostname. The login page reads both keys from the environment and expects an `@my.cspc.edu.ph` account.

### 3. Prepare the database

Create a local database using your MySQL administration tool, then import the supplied snapshot:

```sh
mysql -u your_database_user -p lost_and_found < lost_and_found.sql
```

Import into an empty local database. The snapshot contains data and table definitions, including triggers with `root@localhost` definers; review those definitions and adapt them to your database permissions before importing.

Active migrations are in `app/Database/Migrations/`. Review migration status and schema differences before applying further migrations to an imported snapshot. Files in `temp_migrations/` are inactive reference files.

### 4. Start the web application

Ensure PHP can write to `writable/` and the upload directories used by your installation, then run:

```sh
php spark serve
```

Open **http://localhost:8080/**. Sign in with an account provisioned in your local database. This README does not define a demo account.

## Email configuration

Configure outgoing mail through `app/Config/Email.php` or its corresponding environment overrides, including `email.SMTPHost`, `email.SMTPUser`, `email.SMTPPass`, `email.SMTPPort`, and `email.SMTPCrypto`.

For Gmail reply polling, configure these environment values and enable PHP IMAP support:

```dotenv
GMAIL_EMAIL = your_mailbox@gmail.com
GMAIL_APP_PASSWORD = your_app_password
GMAIL_IMAP_HOST = imap.gmail.com
GMAIL_IMAP_PORT = 993
```

Reply polling is implemented in `app/Controllers/EmailPollerController.php`; its routes are defined in `app/Config/Routes.php`.

## Project structure

```text
app/
  Config/             Application settings, routes, and filters configuration
  Controllers/        Web and API request handlers
  Database/           Active migrations and seeders
  Filters/            Authentication and access checks
  Helpers/            Shared helper functions and services
  Libraries/          Application libraries
  Models/             Database access
  Views/              Pages and layouts
public/
  assets/             Browser JavaScript and styles
  images/             Interface images
  uploads/            Uploaded item images
  index.php           Web entry point
system/               Bundled CodeIgniter framework
writable/             Runtime cache, logs, sessions, and uploads
tests/                PHPUnit tests and fixtures
docs/                 Maintenance records and historical notes
temp_migrations/      Inactive migration references
lost_and_found.sql    Database snapshot
server.js             Separate Node.js API source
```

`server.js` references local services such as `matchingService` and `notificationService` that are absent from this copy, and no Node.js package manifest is included. Running that service requires restoring its dependencies and reviewing its environment settings. The PHP web application starts through `spark` or `public/index.php`.

## Testing

After installing Composer development dependencies, run:

```sh
composer test
```

The suite includes application health checks, database and session examples, and user deletion tests. Database tests require a compatible test schema. Configure the `tests` database group in `app/Config/Database.php` or through `database.tests.*` environment values; the current default uses SQLite in memory.

See [testing guidance](tests/README.md) and [user deletion test documentation](tests/USER_DELETION_TEST_DOCUMENTATION.md) for additional context.

## Hosting

Point the web server's document root at `public/`, configure the application URL, and use `CI_ENVIRONMENT = production`. Keep `writable/` writable by the PHP process. Apache rewrite rules are provided in `public/.htaccess`.

## Maintenance

Historical implementation notes are kept in [docs/history/](docs/history/). They may refer to files removed during cleanup. See [maintenance and recovery notes](docs/README.md) for the cleanup manifests and recovery archive locations.

## License

See [LICENSE](LICENSE) for the included MIT license and CodeIgniter copyright notices.
