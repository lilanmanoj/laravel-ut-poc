FROM alpine:3.18

# Enable all main and community repositories
RUN sed -i 's/^#//g' /etc/apk/repositories && \
    grep -q "community" /etc/apk/repositories || \
    echo "https://dl-cdn.alpinelinux.org/alpine/$(grep VERSION_ID /etc/os-release | cut -d= -f2 | tr -d '\"')/community" >> /etc/apk/repositories && \
    apk update

# Install Apache, PHP, Composer, and dependencies
# php81 will be used as Laravel 8 and Laravel 10 is commonly supported on PHP 8.1
RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    php81 \
    php81-fpm \
    php81-fileinfo \
    php81-gd \
    php81-opcache \
    php81-zip \
    php81-bcmath \
    php81-exif \
    php81-ftp \
    php81-iconv \
    php81-dom \
    php81-cli \
    php81-mysqli \
    php81-pdo \
    php81-pdo_mysql \
    php81-mbstring \
    php81-session \
    php81-tokenizer \
    php81-xml \
    php81-xmlwriter \
    php81-curl \
    php81-openssl \
    php81-phar \
    php81-ctype \
    php81-json \
    php81-pecl-xdebug \
    mysql-client \
    curl \
    unzip \
    git \
    composer \
    supervisor

# Enable Apache + PHP integration
RUN echo "LoadModule proxy_module modules/mod_proxy.so" >> /etc/apache2/httpd.conf && \
    echo "LoadModule proxy_fcgi_module modules/mod_proxy_fcgi.so" >> /etc/apache2/httpd.conf && \
    echo "LoadModule rewrite_module modules/mod_rewrite.so" >> /etc/apache2/httpd.conf && \
    echo "<FilesMatch \\.php\$>" >> /etc/apache2/httpd.conf && \
    echo "    SetHandler \"proxy:fcgi://127.0.0.1:9000\"" >> /etc/apache2/httpd.conf && \
    echo "</FilesMatch>" >> /etc/apache2/httpd.conf && \
    mkdir -p /run/apache2 && chown apache:apache /run/apache2

# Set other essential Apache configurations
RUN echo "ServerName localhost" >> /etc/apache2/httpd.conf && \
    echo "DocumentRoot \"/var/www/html/public\"" >> /etc/apache2/httpd.conf && \
    echo "DirectoryIndex index.php index.html" >> /etc/apache2/httpd.conf

RUN echo '<Directory "/var/www/html/public">' >> /etc/apache2/httpd.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/httpd.conf && \
    echo '    AllowOverride All' >> /etc/apache2/httpd.conf && \
    echo '    Require all granted' >> /etc/apache2/httpd.conf && \
    echo '</Directory>' >> /etc/apache2/httpd.conf

# Working directory → project root (mounted volume)
WORKDIR /var/www/html

# Get and install Laravel 10
RUN composer create-project laravel/laravel laravel-app "10.*"

# Set Apache DocumentRoot to Laravel public directory
RUN sed -i 's#DocumentRoot ".*"#DocumentRoot "/var/www/html/public"#' /etc/apache2/httpd.conf

# Expose port
EXPOSE 80

# Copy configs + entrypoint
COPY supervisord.conf /etc/supervisord.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
