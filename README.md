# Skeleton

```sh
git clone git@github.com:qopcomputers/skeleton.git <my_dir>
cd <my_dir>
rm -Rf .git/
git init
composer install
nano App/config/config.local.neon # provide your database configuration
php www/index.php orm:schema:up --dump-sql --force
php www/index.php user:create
rm -Rf temp/cache temp/btfj.dat
chmod -R 777 temp/ log/ www/assets/
```
