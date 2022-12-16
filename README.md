## Tech Stack
- PHP 8.1
- Laravel 10
- MySql 5.7

## Project Setup Instructions
For easy setup this project is built using docker, so to continue you need to install docker and docker-compose first.
Once they are installed, continue with the follwoing steps:

1. Run this command in the root directory: cp .env.example .env
2. Set following DB details in .env:
    - DB_USERNAME=
    - DB_PASSWORD=
3. For sending emails set the following details in .env: (For testing purposes [Mailtrap](https://mailtrap.io) can be used)
    - MAIL_MAILER=smtp
    - MAIL_HOST=
    - MAIL_PORT=
    - MAIL_USERNAME=
    - MAIL_PASSWORD=
    - MAIL_ENCRYPTION=
4. Run this command in the root directory: docker-compose up -d --build
5. Once container is up, run this command to ssh inside the container: docker-compose exec app bash
6. Inside the container run the following commands:
    - composer install
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed
7. Access following URL for the order API: localhost:81/api/order
8. For running tests, run the following command inside the container: ./vendor/bin/phpunit
