# Используем образ PHP 5.6 с Apache
FROM php:5.6-apache

# Настраиваем архивные репозитории Debian Stretch, удаляя строчку с 'stretch-updates'
RUN sed -i 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/security.debian.org/d' /etc/apt/sources.list && \
    echo "Acquire::Check-Valid-Until false;" > /etc/apt/apt.conf.d/99no-check-valid && \
    apt-get update -o Acquire::AllowInsecureRepositories=true && \
    apt-get install -y --no-install-recommends \
    unzip \
    curl && \
    rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем файлы проекта в контейнер
COPY . /var/www/html

# Устанавливаем права для веб-сервера Apache
RUN chown -R www-data:www-data /var/www/html

# Устанавливаем рабочую директорию
WORKDIR /var/www/html
