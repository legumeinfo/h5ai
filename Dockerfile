FROM node:14-alpine AS builder

WORKDIR /app
COPY package*.json .
RUN npm ci

COPY ghu.js .
COPY src src
RUN npm run build

FROM php:8.1-apache

COPY --from=builder /app/build/_h5ai ./_h5ai

RUN chmod ugo+w _h5ai/private/cache _h5ai/public/cache

COPY --chmod=444 ./httpd-h5ai.conf /etc/apache2/sites-enabled/h5ai.conf

# needed by httpd-h5ai.conf
RUN a2enmod rewrite headers

VOLUME /mnt

ENTRYPOINT ln -s /mnt/* . && exec apache2-foreground
