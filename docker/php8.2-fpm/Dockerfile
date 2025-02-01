FROM php:8.2-fpm

RUN sed -i 's/9000/9063/' /usr/local/etc/php-fpm.d/zz-docker.conf

ARG TIMEZONE
ENV APCU_VERSION=5.1.21

ENV NODE_VERSION=18.12.1
ENV NVM_DIR=/usr/local/nvm

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} myuser && \
    useradd -m -u ${USER_ID} -g ${GROUP_ID} -s /bin/bash myuser

RUN apt-get update && apt-get install -y \
        apt-utils \
        build-essential \
        curl \
    && apt-get install -y \
        gnupg \
        libssl-dev \
        openssl \
        git \
        unzip \
        zip \
        xvfb \
        zlib1g-dev \
        python3  \
    && apt-get install -y \
     #   nodejs \
        libxrender1 \
        libfontconfig \
        libxtst6 \
        libzip-dev \
        xz-utils \
        vim \
        nano \
        zlib1g-dev \
        libpng-dev\
        graphviz \
        default-mysql-client \
        libicu-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version
#Instal nvm for nodejs versioning
RUN mkdir -p $NVM_DIR \
    && curl https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

# Type docker-php-ext-install to see available extensions
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && apt-get install -y \
    yarn

#INSTALL APCU
RUN pecl install apcu-$APCU_VERSION && docker-php-ext-enable apcu
RUN echo "extension=apcu.so" > /usr/local/etc/php/php.ini
RUN echo "apc.enable_cli=1" > /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" > /usr/local/etc/php/php.ini
#APCU

RUN php --ini
RUN php --info | grep apc

RUN docker-php-ext-install pdo pdo_mysql exif

ADD symfony.ini /etc/php8.2/conf.d/
ADD symfony.ini /usr/local/etc/php/conf.d/
ADD symfony.ini /etc/php8.2/cli/conf.d/
ADD xdebug.ini  /etc/php8.2/conf.d/
ADD xdebug.ini /usr/local/etc/php/conf.d/

ADD symfony.pool.conf /etc/php8.2/php-fpm.d/
ADD symfony.pool.conf /usr/local/etc/php/conf.d/

RUN docker-php-ext-install pcntl

RUN \
apt-get update && \
apt-get install libldap2-dev -y && \
rm -rf /var/lib/apt/lists/* && \
docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
docker-php-ext-install ldap

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions http

# Install Redis extension for PHP
RUN pecl install redis && docker-php-ext-enable redis

RUN echo 'alias codecept="vendor/codeception/codeception/codecept"' >> ~/.bashrc
RUN echo 'alias sf="php bin/console"' >> ~/.bashrc
USER myuser
WORKDIR /var/www/bluebinary

CMD ["php-fpm"]
EXPOSE 9063
