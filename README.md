# Deeday API

## Prerequisites

+ PHP >= 7.2
+ Postgresql 11
+ Nginx

## Installation

Run `composer install` to install the dependencies.

Run
```sh
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
to generate a pair of key to enable JWTs.

Copy `config/parameters.yml.dist` to `config/parameters.yml` and edit to match with your database credentials

## Nginx configuration

Ask [Neil Richter](mailto:neil.richter@deeday.io) for Nginx file configuration.

### Help

For assistance, contact [Neil Richter](mailto:neil.richter@deeday.io).
