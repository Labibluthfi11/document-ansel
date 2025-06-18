FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Override konfigurasi nginx biar client_max_body_size kebaca
COPY .docker/nginx.conf /opt/docker/etc/nginx/conf.d/99-custom.conf

ENV WEB_DOCUMENT_ROOT=/app/public \
    PHP_MEMORY_LIMIT=512M \
    PHP_UPLOAD_MAX_FILESIZE=50M \
    PHP_POST_MAX_SIZE=50M

EXPOSE 80
