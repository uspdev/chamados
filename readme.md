# Sobre o projeto

Sistema que gerencia o fluxo de chamados técnicos de informática.

Bibliotecas necessárias do php:
    apt install php7.3-sybase php7.3-mysql php7.3-xml php7.3-intl php7.3-mbstring php7.3-gd php7.3-curl

Criar user e banco de dados:
	grant all privileges on chamados.* to chamados@'%' identified by 'chamados';

Instruções para a instalação do projeto:
é necessário a instalação do composer para prosseguir
    composer install
	cp .env.example .env
	php artisan key:generate
	php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force
    php artisan migrate
	php artisan serve


