FROM php:8.3-alpine3.19

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV APP_SECRET 62054df23413672fc744c4d47a422b8a

# Install system dependencies
RUN apk update && apk add --no-cache \
    nginx \
    git \
    openssh \
    openssl-dev \
    openldap-dev \
    curl-dev \
    libcurl \
    # PHP extensions via alpine packages
    php \
    php-fpm \
    php-opcache \
    php-json \
    php-openssl \
    php-curl \
    php-zlib \
    php-xml \
    php-phar \
    php-intl \
    php-dom \
    php-xmlreader \
    php-ctype \
    php-session \
    php-mbstring \
    php-gd \
    php-pecl-apcu \
    php-iconv \
    php-ldap \
    php-sodium \
    php-tokenizer \
    php-simplexml \
    && rm -f /etc/nginx/conf.d/default.conf

# Configure nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/www.conf /etc/php/php-fpm.d/www.conf
COPY docker/php.ini /etc/php/conf.d/custom.ini

# Copy project files
COPY . /app

# Install additional PHP extensions
RUN apk add --no-cache $PHPIZE_DEPS \
    && docker-php-ext-install -j$(nproc) \
    opcache \
    mysqli \
    pdo \
    pdo_mysql \
    curl

# Set root password
RUN echo "root:Docker!" | chpasswd

# Change file ownership
RUN chown -R www-data:www-data /app

# Set working directory
WORKDIR /app

# Create necessary directories and adjust permissions
RUN mkdir -p var/cache/prod \
    && mkdir -p var/cache/dev \
    && mkdir -p var/cache/test \
    && mkdir -p var/log \
    && touch var/log/prod.deprecations.log \
    && chown -R www-data:www-data var/ \
    && chmod -R ug+rwX var/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose port 8000
EXPOSE 8000

# Default command to start Symfony application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]