FROM webdevops/php-nginx:8.2-alpine AS app

COPY docker/nginx/vhost.conf /opt/docker/etc/nginx/vhost.conf

WORKDIR /app

COPY --chown=1000:1000 bin bin/
COPY --chown=1000:1000 config config/
COPY --chown=1000:1000 migrations migrations/
COPY --chown=1000:1000 public public/
COPY --chown=1000:1000 src src/
COPY --chown=1000:1000 templates templates/
COPY --chown=1000:1000 translations translations/
COPY --chown=1000:1000 \
    .env \
    composer.json composer.lock symfony.lock ./

WORKDIR /sampleapi

COPY --chown=1000:1000 docker/api/msg msg/

WORKDIR /app

EXPOSE 80 81 443
