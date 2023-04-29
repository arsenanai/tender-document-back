source .env
mysqldump -u root -p $DB_DATABASE > backups/$DB_DATABASE-$(date +"%d").sql
php artisan down
git fetch --all
git reset --hard origin/main
composer install
php artisan optimize:clear
php artisan up