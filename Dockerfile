FROM node:14-alpine AS builder

WORKDIR /app
COPY package*.json .
RUN npm ci

COPY ghu.js .
COPY src src
RUN npm run build

FROM php:8.1-apache

RUN apt update && apt install -y --no-install-recommends \
  unzip \
  zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=builder /app/build/_h5ai ./_h5ai

RUN chown www-data _h5ai/private/cache _h5ai/public/cache

COPY ./httpd-h5ai.conf /etc/apache2/sites-enabled/h5ai.conf

# needed by httpd-h5ai.conf
RUN a2enmod rewrite headers