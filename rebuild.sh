#!/bin/sh

composer install
sudo rm -Rf temp/cache temp/btfj.dat
php www/index.php orm:schema:up --dump-sql --force
sudo rm -Rf temp/cache temp/btfj.dat
