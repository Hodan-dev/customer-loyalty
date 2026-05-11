FROM php:8.1-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    mysqli \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install -j$(nproc) gd \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY web/ .
COPY db/ /tmp/db/

# Create startup script
RUN echo '#!/bin/bash\n\
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent; do\n\
    echo "Waiting for MySQL..."\n\
    sleep 2\n\
done\n\
echo "MySQL is up - Starting Apache"\n\
apache2-foreground' > /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expose port
EXPOSE 80
