# Symfony

### Dockerise Symfony Application

- [X] [Install Docker](https://docs.docker.com/engine/install/)
- [X] [Install Docker Compose](https://docs.docker.com/compose/install/)
- [X] [Docker PHP & Nginx]()
- [X] [Create Symfony Application](https://symfony.com/doc/current/setup.html)

### Debugging

- [X] [Install Xdebug](https://xdebug.org/docs/install#pecl)
- [X] [Configure Xdebug in PhpStorm](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html)

### Testing

- [X] [Install PHPUnit](https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework)
- [X] [Integrate PHPUnit with a PhpStorm project](https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework)

### Clean Architecture

- [X] [Onion Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [X] [Monolith First](https://martinfowler.com/bliki/MonolithFirst.html)

### Databases and the Doctrine ORM

- [X] [Installing Doctrine](https://symfony.com/doc/current/doctrine.html)
- [X] [Setting up a Database](https://symfony.com/doc/current/the-fast-track/en/7-database.html)
- [X] [Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/xml-mapping.html)
- [X] [Migrations](https://symfony.com/doc/current/doctrine.html#migrations-creating-the-database-tables-schema)
- [X] [Repository](https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository)
- [X] [Database Testing](https://symfony.com/doc/current/testing/database.html)
- [X] [Fixtures](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)

### CQRS

- [X] [CQRS pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/cqrs)
- [X] [Symfony Messenger](https://symfony.com/doc/current/messenger.html)

### Auth

- [X] [JWT Authentication](https://github.com/lexik/LexikJWTAuthenticationBundle)
- [X] [JWT Refresh Token](https://github.com/markitosgv/JWTRefreshTokenBundle)

### Static analysis tool

- [X] [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [X] [Deptrac](https://qossmic.github.io/deptrac/)
- [X] [PHPStan](https://github.com/phpstan/phpstan)

### Task

Получение курсов, кроскурсов ЦБ.
Необходимо написать небольшой REST API сервис как минимум с одним методом, который будет возвращать курсы/кроскурсы валют по данным ЦБ в формате JSON.
Требования:
- [X] на входе: дата, код котируемой валюты, код базовой валюты (по-умолчанию RUB)
- [X] получать курсы с cbr.ru
- [X] на выходе: значение курса и разница с предыдущим торговым днем
- [X] кешировать данные cbr.ru
  Реализация подразумевает не только код, но и окружение, которое можно запустить через docker-compose.
  Ограничений на использование фреймворков/сторонних пакетов нет, но желательно использовать либо нативный PHP, либо Symfony.
  Для получения данных ЦБ подойдёт любой способ. Один из самых простых и достоверных - https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?op=GetCursOnDate

### HOWTO
CRON

php bin/console app:currencies:parse [YYYY-mm-dd] - Запуск парсинга курсов валют в БД

[YYYY-mm-dd] - дата забора данных (опционально)

REST API

/api/currency/date/{YYYY-mm-dd}/code/{XXX1}/{XXX2}

{YYYY-mm-dd} - Дата курса валют
{XXX1} - Курс волюты
{XXX2} - Курс валюты (базовый)

Пример запуска

curl -H "Authorization: Bearer {token}" http://localhost:888/api/currency/date/2022-10-01/code/EUR/RUB   

{token} - JWT Token