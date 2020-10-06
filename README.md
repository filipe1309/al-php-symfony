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