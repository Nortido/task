FROM php:7.0-fpm

#####################################
# APT-GET:
#####################################
RUN apt-key adv --keyserver hkp://pgp.mit.edu:80 --recv-keys 573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62 \
	&& echo "deb http://nginx.org/packages/debian/ jessie nginx" >> /etc/apt/sources.list \
	&& apt-get update \
	&& apt-get install --no-install-recommends --no-install-suggests -y \
		ca-certificates \
		nginx \
		nginx-module-xslt \
		nginx-module-geoip \
		nginx-module-image-filter \
		nginx-module-perl \
		nginx-module-njs \
		gettext-base \
		libmemcached-dev \
		libz-dev \
		libpq-dev \
		libjpeg-dev \
		libpng12-dev \
		libfreetype6-dev \
		libssl-dev \
		libmcrypt-dev \
		libpq-dev \
		libmysqlclient-dev \
		nano \
		zlib1g-dev \
		libicu-dev \
		g++ \
		git \
		ssh \
		libc-client-dev \
		libkrb5-dev \
    && rm -r /var/lib/apt/lists/* \
	&& apt-get clean

RUN docker-php-ext-install -j$(nproc) iconv mcrypt zip pdo mysqli pdo_mysql \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl \
	&& docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap
#####################################
# PHP-FPM conf
#####################################
COPY container/templates/php.ini.php /usr/local/etc/php/conf.d/php.ini.php
RUN php /usr/local/etc/php/conf.d/php.ini.php > /usr/local/etc/php/conf.d/php.ini && \
rm /usr/local/etc/php/conf.d/php.ini.php && \
rm /usr/local/etc/php-fpm.d/www.conf

COPY container/config/php.pool.conf /usr/local/etc/php-fpm.d/php.pool.conf

#####################################
# NGINX conf
#####################################
COPY container/config/nginx.conf /etc/nginx/nginx.conf
COPY container/config/laravel.conf /etc/nginx/conf.d/laravel.conf
RUN rm /etc/nginx/conf.d/default.conf

#Startup files
COPY container/run.sh /tmp/
RUN chmod +x /tmp/run.sh

#####################################
# Non-Root User Mildberry:
#####################################
RUN groupadd -g 1000 mildberry && \
useradd -u 1000 -g mildberry -m mildberry

ADD . /var/www/html/

CMD ["/tmp/run.sh"]