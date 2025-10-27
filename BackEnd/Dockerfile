FROM php:8.2-fpm

# Instalar Nginx e outras dependências
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    nginx \
    gnupg apt-transport-https

# Instalar extensões PHP (as que já existiam)
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# ===================================================================
# Correção do Driver SQL Server
# ===================================================================

# Adicionar repositório ODBC da Microsoft
RUN curl https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft-prod.gpg

# 👇👇👇 ESTA É A LINHA QUE FOI CORRIGIDA (de /11/ para /12/) 👇👇👇
RUN curl https://packages.microsoft.com/config/debian/12/prod.list > /etc/apt/sources.list.d/mssql-release.list

# Instalar o driver ODBC e ferramentas da Microsoft
RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 unixodbc-dev

# Instalar extensões PHP (PECL) para SQL Server
RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

# ===================================================================
# Fim da correção
# ===================================================================

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar app
COPY . .

# Copiar config do Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copiar script de start
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Ajusta as permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Laravel setup
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expor a porta que o Nginx está ouvindo
EXPOSE 10000 

# Rodar o script de inicialização
CMD ["start.sh"]