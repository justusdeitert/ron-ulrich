#!/bin/sh
# Wrapper to always add --skip-ssl to mariadb client.
# WP-CLI uses --no-defaults which ignores config files,
# so we intercept and inject --skip-ssl here.

if [ "$1" = "--no-defaults" ]; then
    shift
    exec /usr/bin/mariadb-real --no-defaults --skip-ssl "$@"
else
    exec /usr/bin/mariadb-real --skip-ssl "$@"
fi
