# Changelog

### 2/2/2024 Versão 1.6.5

* Trocando o gerenciamento de usuários local para o do senhaunica-socialite

### 2/2/2024 Versão 1.6.4

* Link para sistema patrimônio e sistema pessoas
  * Permite colocar link para esses 2 sistemas USPdev a fim de facilitar a consulta de informações.
* Relatório de chamados por mês nos detalhes da fila.
* Integrado com uspdev/laravel-tools
* Correção de bugs

### 14/2/2023 Versão 1.5 

* Atualizado as bibliotecas do USPdev e do composer
    * TODO: o `senhaunica-socialite` ainda não está usando `permissions` nesse projeto
    * reorganizado o `.env.exemple`
* Instalado `uspdev/laravel-tools`
    * variável `FORCAR_HTTPS` agora é `LARAVEL_TOOLS_FORCAR_HTTPS`
* Instalado `uspdev/laravel-replicado`
* Corrigindo documentação sobre replicado #349
* Mostrando chamados pendentes de anos anteriores #348

### 5/2022 - Versão 1.4.6

* Adicionado a variável SISTEMA_PATRIMONIO no env.
