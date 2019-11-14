#!/usr/bin/env sh

export DOLLAR='$'

envsubst < /etc/nginx/conf.d/upstream.conf.template > /etc/nginx/conf.d/upstream.conf
envsubst < /etc/nginx/symfony-production.conf.template > /etc/nginx/sites-enabled/010-symfony.conf

nginx -g "daemon off;"

