FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

ENV WEB_DOCUMENT_ROOT=/app/public \
    PHP_MEMORY_LIMIT=512M \
    PHP_UPLOAD_MAX_FILESIZE=50M \
    PHP_POST_MAX_SIZE=50M \
    NGINX_CLIENT_MAX_BODY_SIZE=50m

EXPOSE 80
