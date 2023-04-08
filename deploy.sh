git fetch
git reset --hard origin/master

docker compose exec api composer install --prefer-dist --optimize-autoloader
docker compose exec api php artisan migrate
docker compose exec api php artisan cache:clear
docker compose exec api php artisan optimize:clear
docker compose exec api php artisan route:cache
docker compose exec api php artisan config:cache
docker compose exec api php artisan view:cache
docker compose exec api php artisan optimize

docker compose restart api supervisord

#( cd www/admin ; yarn; yarn build )
