# 接下来是您的主构建阶段
FROM php:8.1-fpm-alpine as builder

# 安装 nginx 和 wget
RUN apk add --no-cache wget nginx

# 安裝 gRPC 依賴
RUN apk add --virtual .build-deps $PHPIZE_DEPS \
    g++ \
    zlib-dev \
    linux-headers \
    && apk add libstdc++ \
    && wget http://getcomposer.org/composer.phar \
    && chmod a+x composer.phar \
    && mv composer.phar /usr/local/bin/composer

RUN pecl install grpc
RUN docker-php-ext-enable grpc

# 複製應用程式代碼
RUN mkdir -p /app
COPY . /app
COPY ./src /app

# 安裝 Composer 依賴
RUN cd /app && /usr/local/bin/composer install --no-dev

# 運行階段
FROM php:8.1-fpm-alpine

# 安裝 nginx 和 gRPC 依賴的 libstdc++
RUN apk add --no-cache nginx libstdc++

# 複製 gRPC 擴展
# COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20210902/grpc.so /usr/local/lib/php/extensions/no-debug-non-zts-20210902/
# COPY --from=builder /usr/local/etc/php/conf.d/docker-php-ext-grpc.ini /usr/local/etc/php/conf.d/

# RUN echo 'extension=/usr/local/lib/php/extensions/no-debug-non-zts-20210902/grpc.so' > /usr/local/etc/php/conf.d/grpc.ini

# 創建必要的目錄
RUN mkdir -p /run/nginx /app

# 複製 nginx 配置和應用程式代碼
COPY --from=builder /app /app
COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20210902/grpc.so /usr/local/lib/php/extensions/no-debug-non-zts-20210902/grpc.so
COPY --from=builder /usr/local/etc/php/conf.d/docker-php-ext-grpc.ini /usr/local/etc/php/conf.d/

RUN docker-php-ext-enable grpc
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 設置權限
RUN chown -R www-data: /app

# 啟動腳本
CMD sh /app/docker/startup.sh