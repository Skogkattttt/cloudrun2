# 建構階段
FROM php:8.1-fpm-alpine as builder

# 安裝 nginx 和 wget
RUN apk add --no-cache wget

# 安裝 gRPC 依賴
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    g++ \
    zlib-dev \
    linux-headers \
    && apk add --no-cache libstdc++ \
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    && apk del .build-deps

# 安裝 Composer
RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"

# 複製應用程式代碼
RUN mkdir -p /app
COPY ./src /app

# 安裝 Composer 依賴
RUN cd /app && /usr/local/bin/composer install --no-dev

# 運行階段
FROM php:8.1-fpm-alpine

RUN apk add --no-cache nginx

# 創建必要的目錄
RUN mkdir -p /run/nginx /app

# 複製 nginx 配置和應用程式代碼
COPY --from=builder /app /app
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 設置權限
RUN chown -R www-data: /app

# 啟動腳本
CMD sh /app/docker/startup.sh