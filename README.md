# Posts Management API

> Posts Management project.

```bash
$ composer install
$ cp .env.example .env
$ php artisan key:generate

Set database configuration: database, username, password

Set mailer(gmail):
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl

$ php artisan migrate
$ php artisan passport:install

Set CONFIRMATION_REGISTRATION_FRONT_URL - Url for frontend application 
Set REMIND_PASSWORD_FRONT_URL - Url for frontend application

API Docs(url): {project-name}/api/documentation
Example: project.localhost/api/documentation

```
