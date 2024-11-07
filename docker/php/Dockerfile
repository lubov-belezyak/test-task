# Используем образ PHP 5.6 с Apache
FROM php:5.6-apache

# Настраиваем архивные репозитории Debian Stretch, удаляя строку с 'stretch-updates'
RUN sed -i 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/security.debian.org/d' /etc/apt/sources.list && \
    echo "Acquire::Check-Valid-Until false;" > /etc/apt/apt.conf.d/99no-check-valid && \
    apt-get update -o Acquire::AllowInsecureRepositories=true && \
    apt-get install -y --no-install-recommends \
    unzip \
    curl && \
    rm -rf /var/lib/apt/lists/*

# Устанавливаем необходимые PHP расширения (PDO и драйвер MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаём папку и настраиваем DocumentRoot для Apache
RUN mkdir -p /var/www/sites && \
    sed -i 's|/var/www/html|/var/www/sites/public|g' /etc/apache2/sites-available/000-default.conf

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Копируем файлы проекта в контейнер
COPY . /var/www/sites

# Устанавливаем права для веб-сервера Apache
RUN chown -R www-data:www-data /var/www/sites && chmod -R 755 /var/www/sites

# Устанавливаем рабочую директорию
WORKDIR /var/www/sites

# Перезагружаем конфигурацию Apache
RUN apachectl -k restart
