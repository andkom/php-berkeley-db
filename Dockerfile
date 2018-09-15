FROM php:7.2-cli

RUN apt-get update && apt-get install -y libdb5.3++-dev

RUN curl -O --referer https://fossies.org/linux/misc/db-18.1.25.tar.gz/ \
        https://fossies.org/linux/misc/db-18.1.25.tar.gz \
    && tar -zxf db-18.1.25.tar.gz && cd db-18.1.25/lang/php_db4/ \
    && phpize \
    && ./configure --with-db4 \
    && make \
    && make install \
    && docker-php-ext-enable db4

RUN curl -O http://download.oracle.com/berkeley-db/db-4.8.30.tar.gz \
    && tar -zxf db-4.8.30.tar.gz \
    && cd db-4.8.30/build_unix \
    && ../dist/configure --enable-cxx \
    && make \
    && make install \
    && cd ../../

RUN docker-php-ext-configure dba --with-db4=/usr/local/BerkeleyDB.4.8 \
    && docker-php-ext-install dba