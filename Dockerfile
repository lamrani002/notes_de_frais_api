# -- On part d'une image php-fpm (version 8.2 par exemple) --
    FROM php:8.2-fpm

    # -- On installe les dépendances système : libzip, etc. --
    RUN apt-get update && apt-get install -y \
        zip \
        unzip \
        git \
        curl \
        libzip-dev \
        && docker-php-ext-install zip pdo pdo_mysql \
        && rm -rf /var/lib/apt/lists/*
    
    # -- Installer Composer --
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    # -- Configurer le répertoire de travail --
    WORKDIR /var/www/html
    
    # -- Vérifier et créer les dossiers nécessaires --
    RUN mkdir -p /var/www/html/storage/framework/views \
        /var/www/html/storage/framework/cache \
        /var/www/html/storage/framework/sessions \
        /var/www/html/bootstrap/cache \
        && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
        && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
    # -- Exposer le port 9000 (par défaut pour php-fpm) --
    EXPOSE 9000
    
    CMD ["php-fpm"]
    