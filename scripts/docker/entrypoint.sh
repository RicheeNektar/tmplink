#!/bin/bash

cd /var/www/html/

while ! bin/console doc:mig:mig
do
  sleep 1
done

rm -rf /var/www/html/var/cache/ /var/www/html/var/log/

cron

apache2ctl -D FOREGROUND
