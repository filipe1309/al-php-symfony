## No Diretorio da app:

// Usar img docker do composer para criar projeto
$ docker run --rm -itv %CD%:/app -w /app composer create-project symfony/skeleton consultorio-alura

// Usar img docker do php para rodar projeto
$ cd consultorio-alura
$ docker run --rm -itv %CD%:/app -w /app -p 8080:8080 php -S 0.0.0.0:8080 -t public

$ composer require annotattion

## ORM Doctrine
$ composer require orm-pack

No . env
DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db

$ bin/console doctrine:database:create

$ bin/console doctrine:migrations:diff

$ bin/console doctrine:migrations:migrate

## From Sqlite to Mysql

### In Windows, initialize MySQL80 Service, on Services (if not yet)

### Dentro do container php
$ bin/console doctrine:database:create

$ bin/console doctrine:migrations:diff

$ bin/console --no-interaction doctrine:migrations:execute --up 'DoctrineMigrations\Version20201006004936'

RUN /bin/bash -c 'php /var/www/html/bin/console --no-interaction --allow-no-migration doctrine:migrations:execute --up \'DoctrineMigrations\Version20201006004936\''

        entrypoint: ["/var/www/html/bin/console", "--no-interaction", "doctrine:migrations:execute", "-vvv", "--up", 'DoctrineMigrations\Version20201006004936']


$ composer require maker

$ bin/console list make

$ bin/console make:entity

$ bin/console make:migration

$ bin/console doctrine:migrations:migrate

bin/console doctrine:database:drop --force

bin/console doctrine:database:create

bin/console make:controller

git tag -a v2.0 -m "Symfony 2"

composer require security

composer require firebase/php-jwt

bin/console make:user

bin/console make:migration

$ bin/console doctrine:migrations:migrate

composer require orm-fixtures --dev

$ bin/console make:fixtures

bin/console security:encode-password

bin/console doctrine:fixtures:load

bin/console make:controller

https://jwt.io/

git tag -a v3.0 -m "Symfony 3"

composer require --dev symfony/phpunit-bridge

bin/phpunit

composer require --dev symfony/browser-kit symfony/css-selector

## Endpoints examples

### Login
GET http://localhost/login
BODY raw (Content-Type: application/json)
{
    "usuario": "usuario",
    "senha": "123456"
}

### Medicos

GET http://localhost/ola?parametro=valor&parametro2=valor2
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

POST http://localhost/medicos
HEADER Authorization: Bearer ACESS_TOKEN(JWT)
BODY raw (Content-Type: application/json)
{
    "crm": 101010,
    "nome": "John Lennon",
    "especialidadeId": 5
}

GET http://localhost/medicos
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/medicos?sort[crm]=ASC&especialidade=1
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/medicos?page=2&itemsPerPage=3
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/medicos/2
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

PUT http://localhost/medicos/1
HEADER Authorization: Bearer ACESS_TOKEN(JWT)
BODY raw (Content-Type: application/json)
{
    "crm": 111111,
    "nome": "Bob Dlan 1.1",
    "especialidadeId": 1
}

DEL http://localhost/medicos/2
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

### Especialidades

POST http://localhost/especialidades
HEADER Authorization: Bearer ACESS_TOKEN(JWT)
BODY raw (Content-Type: application/json)
{
    "descricao": "Especialidade 3"
}

GET http://localhost/especialidades
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/especialidades?sort[descricao]=DESC
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/especialidades/2
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

PUT http://localhost/especialidades/4
HEADER Authorization: Bearer ACESS_TOKEN(JWT)
BODY raw (Content-Type: application/json)
{
    "descricao": "Especialidade 4"
}

DEL http://localhost/especialidades/2
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

GET http://localhost/especialidades/1/medicos
HEADER Authorization: Bearer ACESS_TOKEN(JWT)

