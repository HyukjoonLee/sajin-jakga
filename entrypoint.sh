#!/bin/sh
# Generate Rhymix config if it doesn't exist
php /var/www/html/scripts/setup-config.php

# Execute the original command (php-fpm)
exec php-fpm
