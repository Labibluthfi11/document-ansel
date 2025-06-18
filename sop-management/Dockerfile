FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . .

# Copy file nginx config khusus untuk override ukuran upload
COPY uploads.conf /etc/nginx/conf.d/uploads.conf

RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Environment PHP dan konfigurasi lainnya
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_MEMORY_LIMIT=512M
ENV PHP_UPLOAD_MAX_FILESIZE=50M
ENV PHP_POST_MAX_SIZE=50M

EXPOSE 80
