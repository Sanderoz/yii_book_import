#!/bin/sh

#service rabbitmq-server start
supervisord -c /etc/supervisord.conf

exec "$@"