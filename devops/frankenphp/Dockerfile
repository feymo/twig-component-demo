FROM dunglas/frankenphp

# ajoutez des extensions supplémentaires ici :
RUN install-php-extensions \
    intl \
    zip \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    xsl \
    gd \
    mbstring \
    http \
    @composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY ./devops/frankenphp/.bashrc /root/.bashrc
