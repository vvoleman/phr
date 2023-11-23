docker/wait-for-it.sh mysql:3306 -t 30

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction && \
php bin/console syncer:all:run --no-interaction --no-debug

echo "Setup for API is done!"

apache2-foreground
