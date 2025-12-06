#!/bin/sh


HOST_NAME=$(hostname)
CONF_FILE="/etc/zabbix/zabbix_agentd.conf"
TEMP_CONF="/tmp/zabbix_agentd.conf"


cp $CONF_FILE $TEMP_CONF


echo "" >> $TEMP_CONF


echo "Hostname=$HOST_NAME" >> $TEMP_CONF


/usr/sbin/zabbix_agentd -c $TEMP_CONF &


/usr/sbin/nginx -g 'daemon on;'


exec /usr/local/sbin/php-fpm -F