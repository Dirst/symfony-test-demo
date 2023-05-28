#!/bin/bash

cd /docker-init
./wait-for-it.sh -t 0 db:3306 -- echo "Database is ready!"

cd /var/www

composer install

./bin/console doctrine:migrations:migrate -n 
./bin/console doctrine:fixtures:load -n

php-fpm