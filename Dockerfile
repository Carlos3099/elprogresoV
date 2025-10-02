FROM php:8.2-cli

# Instalar dependencias necesarias para Laravel y DomPDF
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev g++ \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd exif intl bcmath

# Extensiones adicionales útiles para Laravel/DomPDF
RUN docker-php-ext-install mbstring dom

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar composer.json y lock desde src
COPY ./src/composer.json ./src/composer.lock ./

# Instalar dependencias de Composer (sin dev para producción)
RUN composer install --no-scripts --no-autoloader --no-dev || true

# Copiar el resto de la aplicación
COPY ./src .

# Generar autoload optimizado
RUN composer dump-autoload --optimize || true

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
