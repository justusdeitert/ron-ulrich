#!/bin/bash

# Run the WordPress setup script
/devops/scripts/setup-wordpress.sh

# Start PHP-FPM
exec php-fpm