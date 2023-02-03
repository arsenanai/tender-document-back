# Tender document checking server project
## Server requirements
- MySQL database
- PHP v8
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Nginx
- ✨DevOPS engineer ✨

## Installation guide
Guide was documented while using Ubuntu 22.04.1 LTS

For local development install the dependencies and devDependencies and start the server.

```sh
cd tender-document-back
composer install
php -S localhost:8000 -t public
```

For production environments...

```sh
cd tender-document-back
composer install
```

Configure nginx
```sh
cd /etc/nginx/sites-enabled
nano tender
```