# Dockerfile
FROM php:8.2-fpm

# 필요한 라이브러리 및 PHP 확장 설치
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip curl mbstring \
    && pecl install redis-6.2.0 \
    && docker-php-ext-enable redis

# MySQL 소켓 파일 디렉토리 생성
RUN mkdir -p /var/run/mysqld

# Apple Silicon 환경에서 빌드 속도 최적화
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
