ARG PHP_FPM_VERSION
FROM php:${PHP_FPM_VERSION}-fpm

# Install "curl", "libmemcached-dev", "libpq-dev", "libjpeg-dev", "libpng-dev", "libfreetype6-dev", "libssl-dev", "libmcrypt-dev",
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libmcrypt-dev; \
#    if [ ${PHP_FPM_VERSION} = "7.3" ] || [ ${PHP_FPM_VERSION} = "7.4" ] || [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        apt-get install -y --no-install-recommends libwebp-dev; \
#    fi && \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    # Install the PHP pdo_pgsql extention
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
#    if [ ${PHP_FPM_VERSION} = "7.3" ] || [ ${PHP_FPM_VERSION} = "7.4" ] || [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
#        docker-php-ext-configure gd \
#    else \
#         docker-php-ext-configure gd \
#            --enable-gd-native-ttf \
#            --with-jpeg-dir=/usr/lib \
#            --with-freetype-dir=/usr/include/freetype2; \
#    fi && \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

# Install zip extension
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      libzip-dev zip unzip && \
#      if [ ${PHP_FPM_VERSION} = "7.3" ] || [ ${PHP_FPM_VERSION} = "7.4" ] || [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        docker-php-ext-configure zip; \
#      else \
#        docker-php-ext-configure zip --with-libzip; \
#      fi && \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'

# Install extra extensions
RUN set -xe; \
    # Install bcmath
    docker-php-ext-install bcmath  && \
    # Install language utilities
    apt-get install -y zlib1g-dev libicu-dev g++ && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    # Install ImageMagick
#    apt-get install -y libmagickwand-dev imagemagick && \
#    pecl install imagick && \
#    docker-php-ext-enable imagick && \
    # Install Opcache
    docker-php-ext-install opcache && \
    # Image optimizers
    apt-get install -y jpegoptim optipng pngquant gifsicle

# Check php version
RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PHP_VERSION}."

# Copy configuration files
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Configure locale
ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000