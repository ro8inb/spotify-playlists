#!/bin/sh
# Script de démarrage pour gérer les permissions

# Fixer les permissions des répertoires storage et bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Démarrer PHP-FPM
exec php-fpm
