FROM php:8.2-fpm-bullseye

# Csomagok telepítése (beleértve a zabbix-agentet is, ami létrehozza a 'zabbix' usert)
RUN apt-get update && apt-get install -y \
    nginx \
    mariadb-client \
    curl \
    zabbix-agent \
    wget \
    && rm -rf /var/lib/apt/lists/*

# PHP modulok telepítése
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Nginx config könyvtárak (ha szükséges)
RUN mkdir -p /etc/nginx/conf.d \
           /etc/nginx/http.d

# PHP és egyéb konfigok
COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./docker-php-env.conf /usr/local/etc/php-fpm.d/zz-docker-php-env.conf

# Web root létrehozása és munkakönyvtár beállítása
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Zabbix log könyvtár létrehozása és jogosultságok beállítása.
# Mivel a 'zabbix' felhasználó az előző 'apt-get install' lépésben létrejött,
# ez a 'chown' már sikeres lesz.
RUN mkdir -p /var/log/zabbix && chown zabbix:zabbix /var/log/zabbix

# Entrypoint
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# A Zabbix agent a legtöbb Ubuntu alapú disztróban /usr/sbin/zabbix_agentd néven van
CMD ["entrypoint.sh"]