FROM php:8.0-cli

RUN apt-get update \
  && apt-get install -y git make unzip zlib1g-dev \
  && docker-php-ext-install -j$(nproc) sysvmsg sysvsem sysvshm \
  && pecl install -o -f grpc protobuf \
  && docker-php-ext-enable grpc protobuf \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
  && mkdir -p /app/

WORKDIR /app
