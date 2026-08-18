FROM php:8.1-apache

# Install ekstensi sistem yang dibutuhkan Omeka
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    imagemagick \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql exif

# Aktifkan mod_rewrite untuk Apache
RUN a2enmod rewrite

# Copy seluruh file aplikasi ke dalam container
COPY . /var/www/html/

# Atur ownership ke www-data agar Omeka bisa menulis file
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 775 /var/www/html/files/
