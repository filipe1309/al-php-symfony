#!/bin/bash
echo "Doctrine Script..."
set -e

sleep 5
php /var/www/html/bin/console --no-interaction doctrine:migrations:execute --up 'DoctrineMigrations\Version20201006004936'
exec "$@"