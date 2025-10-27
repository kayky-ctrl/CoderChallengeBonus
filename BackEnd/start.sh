#!/bin/sh

# Inicia o PHP-FPM em background
php-fpm &

# Inicia o Nginx em "foreground" (para manter o container rodando)
nginx -g "daemon off;"