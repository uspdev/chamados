# Sobre o projeto

Sistema que gerencia o fluxo de chamados técnicos de informática.

Está sendo modificado para poder atender chamados de qualquer setor da unidade.


## Requisitos

* Git
* Composer

Bibliotecas necessárias do php:

    apt install php-sybase php-mysql php-xml php-intl php-mbstring php-gd php-curl

## Instalação

Depois de instalar os requisitos, faça um clone do projeto 

	git clone git@github.com/uspdev/chamados

Criar user e banco de dados:

	grant all privileges on chamados.* to chamados@'%' identified by 'chamados';

## Configuração

é necessário a instalação do composer para prosseguir

    composer install
	cp .env.example .env
	php artisan key:generate
    php artisan migrate
	php artisan serve


