release: dokku-deploy/release.sh
web: apache2-foreground
worker: php artisan queue:work --sleep=3 --tries=3 --timeout=60
