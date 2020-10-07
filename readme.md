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

	git clone git@github.com:uspdev/chamados

Criar user e banco de dados:

	grant all privileges on chamados.* to chamados@'%' identified by 'chamados';

## Configuração

é necessário a instalação do composer para prosseguir

    composer install
	cp .env.example .env
	php artisan key:generate
    php artisan migrate --seed
	php artisan serve


## Problemas e soluções

Ao rodar pela primeira vez com apache, as variáveis de ambiente relacionadas ao replicado não ficam disponíveis. Nesse caso é necessário restartar o apache.

https://www.php.net/manual/pt_BR/function.getenv.php#117301


Fresh start

	php artisan migrate:fresh --seed

## Roadmap

O sistema de chamados foi transferido da FFLCH para o USPDev. 
Nesse escopo, os objetivos das mudanças em andamento são:

* Adaptar para o uso por várias unidades
* Expandir para o uso por outros setores como por exemplo o serviço de manutenção

### Etapas

1. Criar tabela de setores que podem prestar serviços
2. Renomear categorias para filas
	* o admin de cada setor poderá criar filas
	* o criador da fila é gerente dela, que pode distribuir chamados (triagem)
	* o gerente pode adicionar atendentes que poderão ser atribuídos aos chamados bem como atribuir para si mesmo (autotriagem)
	* a fila possui visibilidade que controla qual grupo da comunidade pode abrir chamado
3. Criar as associações entre filas e users, setores e users, chamado e users, etc
	* a relação entre filas e users será um relacionamento n:n com atributo de tipo: atendente, gerente, etc
	* a relação setores e users será n:n com atributo tipo: admin, etc
4. reorganizar a tabela de chamados
	* possui atributos comuns e atributos específicos
	* Atributos comuns: status, complexidade, assunto, descricao, criador, atribuido_para, datas de abertura, fechamento, atribuição, etc
	* Atributos especificos: são aqueles criados pelo gerente da fila, como por exemplo: patrimonio, sala/predio, outros campos de texto, campos de radio e checkbox, urgência do atendimento, etc
5. permitir anexos (pdf e fotos)
6. Diferenciar "meus chamados" e "chamados atribuidos para mim" e "chamados em minhas filas"

### Andamento

1. Feito
2. Foi criado a tabela filas para posteriormente migrar os dados de categorias