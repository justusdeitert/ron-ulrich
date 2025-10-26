#!/bin/bash

# Run the WordPress setup script
/usr/local/bin/setup-wordpress.sh

# Start PHP-FPM
exec php-fpm
