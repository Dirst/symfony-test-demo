FROM php:8-fpm

ARG APP_USER="user"
RUN set -x && useradd -u 1000 -m -s /bin/bash ${APP_USER}
ENV APP_USER=${APP_USER}

# MC,COMPOSER,CURL,WGET,GIT
RUN set -x \
&& apt-get update && apt-get install -y \
curl \
wget \
git \
mc \
pv \
mariadb-client

# PHP DEPENDENCIES
RUN apt-get install -y \
libxml2-dev \
libzip-dev \
libssh2-1-dev \
libbz2-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
&& pecl install ssh2-1.3.1 \
&& pecl install apcu \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) zip opcache bz2 bcmath zip pdo_mysql gd \
&& docker-php-ext-enable ssh2 \
&& docker-php-ext-enable apcu

#FINAL
WORKDIR /var/www

# COLOR SCHEME
ENV TERM=xterm-color

# ADD VENDOR BIN TO $PATH
ENV PATH /var/www/vendor/bin:$PATH

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["/docker-init/init.sh"]