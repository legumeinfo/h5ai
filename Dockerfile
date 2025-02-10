FROM node:22-alpine AS builder

WORKDIR /app
COPY package*.json .
RUN npm ci

COPY ghu.js .
COPY src src
ENV NODE_OPTIONS=--openssl-legacy-provider
RUN npm run build

FROM php:8.4-apache

COPY --from=builder /app/build/_h5ai ./_h5ai

RUN chown www-data _h5ai/private/cache _h5ai/public/cache

COPY ./httpd-h5ai.conf /etc/apache2/sites-enabled/h5ai.conf
