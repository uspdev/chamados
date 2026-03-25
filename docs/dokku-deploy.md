# Deploy do Chamados com Dokku

## Instalação do Docker e do Dokku

```bash=
wget -NP . https://dokku.com/install/v0.36.7/bootstrap.sh
sudo DOKKU_TAG=v0.36.7 bash bootstrap.sh
```

Adicione sua chave SSH pública ao dokku:

```bash=
echo 'conteudo-da-sua-chave-publica' | sudo dokku ssh-keys:add admin
```

## No servidor Dokku

### Criação e configuração do app

Variáveis de ambiente:

```bash=
export ADMIN_EMAIL="leandro@if.usp.br"
export APP_NAME="chamados"
export APP_DOMAIN="$APP_NAME.if.usp.br"
export MYSQL_NAME="mysql_$APP_NAME"
```

Instalação dos plugins necessários:

```bash=
sudo dokku plugin:install https://github.com/dokku/dokku-mysql.git --name mysql
sudo dokku plugin:install https://github.com/dokku/dokku-maintenance.git
sudo dokku plugin:install https://github.com/dokku/dokku-letsencrypt.git
```

Criação do app:

```bash=
dokku apps:create $APP_NAME
dokku checks:disable $APP_NAME
dokku domains:set $APP_NAME $APP_DOMAIN
dokku letsencrypt:set $APP_NAME email $ADMIN_EMAIL
```

O Dokku faz o link do _service_ MySQL com a aplicação através da variável de ambiente **DATABASE_URL**. O Laravel já tem a variável no arquivo `config/database.php`. Sendo assim, só precisamos criar o banco de dados e fazer o link com a aplicação. No ".env" basta setar **DB_CONNECTION="mysql"**, os parâmetros da conexão já estarão na **DATABASE_URL**.

```bash=
dokku mysql:create $MYSQL_NAME
dokku mysql:link $MYSQL_NAME $APP_NAME
```

Criação das variáveis de ambiente para reproduzir o `.env`:

```bash=
dokku config:set --no-restart $APP_NAME \
    APP_NAME="Chamados" \
    APP_KEY="base64:$(openssl rand -base64 32)" \
    APP_ENV="production" \
    APP_DEBUG="false" \
    APP_URL="https://chamados.example.com" \
    UPLOAD_MAX_FILESIZE="16" \
    USAR_REPLICADO="true" \
    DB_CONNECTION="mysql" \
    MAIL_MAILER="smtp" \
    MAIL_HOST="smtp.example.com" \
    MAIL_PORT="587" \
    MAIL_ENCRYPTION="tls" \
    MAIL_USERNAME="johndoe" \
    MAIL_PASSWORD="senhaForte" \
    MAIL_FROM_ADDRESS="mensageiro@example.com" \
    MAIL_FROM_NAME="Sistema de Chamados" \
    USP_THEME_SKIN="if" \
    SENHAUNICA_KEY="if" \
    SENHAUNICA_SECRET="leveiocavaloparabeberaguanoacude" \
    SENHAUNICA_CALLBACK_ID="36" \
    SENHAUNICA_ADMINS="7654321,1234567" \
    SENHAUNICA_GERENTES="7654321,1234567" \
    SENHAUNICA_CODIGO_UNIDADE="43" \
    REPLICADO_HOST="replicado.usp.br" \
    REPLICADO_PORT="5000" \
    REPLICADO_DATABASE="replicado" \
    REPLICADO_USERNAME="replicante" \
    REPLICADO_PASSWORD="etnacilper" \
    REPLICADO_CODUNDCLG="43" \
    REPLICADO_CODUNDCLGS="43" \
    REPLICADO_SYBASE="true" \
    LARAVEL_TOOLS_FORCAR_HTTPS="true" \
    QUEUE_CONNECTION="database" \
    CHAMADO_CAMPOS_A_ESQUERDA=""
```

### Volumes da aplicação

```bash=
dokku storage:ensure-directory $APP_NAME
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/storage:/var/www/html/storage
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/bootstrap-cache:/var/www/html/bootstrap/cache
```

## Na máquina de desenvolvimento

### Dockerfile

Arquivo `Dockerfile` na pasta raiz do projeto:

```Dockerfile=
FROM php:8.3-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    unixodbc \
    unixodbc-dev \
    freetds-dev \
    freetds-bin \
    tdsodbc \
    libsybdb5 \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_dblib \
    gd \
    mbstring \
    zip \
    xml \
    bcmath \
    pcntl \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "[FreeTDS]" >> /etc/odbcinst.ini \
    && echo "Description = FreeTDS Driver" >> /etc/odbcinst.ini \
    && echo "Driver = /usr/lib/x86_64-linux-gnu/odbc/libtdsodbc.so" >> /etc/odbcinst.ini \
    && echo "Setup = /usr/lib/x86_64-linux-gnu/odbc/libtdsS.so" >> /etc/odbcinst.ini

RUN a2enmod rewrite

COPY dokku-deploy/apache-php.conf /etc/apache2/conf-available/
RUN a2enconf apache-php

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
```

### Configurações personalizadas do PHP

Arquivo `./dokku-deploy/apache-php.conf` na pasta raiz do projeto (opcional, mas fica para referência caso precisemos alterar as configs do PHP):

```ini=
php_value upload_max_filesize 2M
php_value post_max_size 2M
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_time 300
php_value max_input_vars 1000
php_value max_file_uploads 2
```

### Arquivo de release

Arquivo `./dokku-deploy/release.sh`:

```bash=
#!/bin/bash

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
php artisan migrate --force
```

### Procfile

O arquivo `Procfile`, na raiz do projeto, tem as linhas de comando que o Dokku vai executar durante o deploy (após o build):

```=
release: dokku-deploy/release.sh
web: apache2-foreground
worker: php artisan queue:work --sleep=3 --tries=3 --timeout=60
```

### Criação do git remote e deploy

Configuração do _git remote_ para o deploy:

```bash=
git remote add dokku dokku@dokku-server:chamados
```

Deploy:

```bash=
git push dokku <algum-branch>:main
```

## Pós-deploy

Depois de subir a aplicação, entre no container e execute:

```bash=
# Rodar o worker, que vai cuidar das filas de e-mails
dokku ps:scale chamados worker=1

# Se souber o nome do container e quiser rodar só uma linha de comando:
docker container exec datagrad.web.1 bash -c 'composer install --no-dev --optimize-autoloader && php artisan migrate --force'

# Outra opção, entrando no container
dokku enter $APP_NAME
composer install --no-dev --optimize-autoloader
php artisan migrate
```
