FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && npm install && npm run build

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_MEMORY_LIMIT=512M
ENV UPLOAD_MAX_FILESIZE=50M
ENV POST_MAX_SIZE=50M
ENV CLIENT_MAX_BODY_SIZE=50M

EXPOSE 80
