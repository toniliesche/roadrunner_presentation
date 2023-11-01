#!/bin/bash

cd /var/www/webapp
su -c "rr serve" -s /bin/sh www-data
