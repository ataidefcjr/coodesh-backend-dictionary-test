
# Backend Dictionary

>  This is a challenge by [Coodesh](https://coodesh.com/)

Este repositório contém a aplicação API para o projeto **Dictionary**, construída com **Laravel**. 


## Sumário
- [Funcionalidades](#funcionalidades)
- [Tecnologias ](#tecnologias-usadas)
- [Disposição do Banco de Dados](#disposição-do-banco-de-dados)
- [Setup Manual](#setup-manual)
- [Setup com Docker](#docker-setup)
- [Sobre o desafio](#desafios)


## Funcionalidades:
Com esta API o usuário é capaz de realizar as seguintes ações:

* Se registrar.
* Fazer login.
* Fazer logout.
* Pesquisar sobre uma palavra (Fonética, sinônimos, antônimos, etc...).
* Favoritar uma palavra.
* Visualizar seu histórico de palavras pesquisadas e favoritos.


## Tecnologias Usadas

* **PHP**
    * Laravel
        * Sanctum
        * Ramsey
    * Composer
* Scramble
* Docker
* MySQL 

## Disposição do Banco de Dados
As principais tabelas do banco de dados estão distribuídos como a seguir

<table border='1px'>
<thead>
<tr>
<td><strong>words</strong></td>
<td><strong>favorite_words</strong></td>
<td><strong>history_words</strong></td>
<td><strong>users</strong></td>
</tr>
<thead>
<tbody>
<tr>
<td>id</td>
<td>id</td>
<td>id</td>
<td>id (uuid)</td>
</tr>
<tr>
<td>word</td>
<td>user_id (unique, foreign)</td>
<td>user_id (unique, foreing)</td>
<td>name</td>
</tr>
<tr>
<td></td>
<td>word (unique)</td>
<td>word (unique)</td>
<td>email (unique)</td>
</tr>
<tr>
<td></td>
<td>added</td>
<td>added</td>
<td>password (hashed)</td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td>created at</td>
</tr>
</tbody>
</table>

## Uso

- [Setup Manual](#setup-manual)
- [Setup com Docker](#docker-setup)

## Setup Manual

Este é um passo a passo de como rodar a aplicação manualmente.

### Pré-requisitos
- [**Git**](https://git-scm.com/downloads)
- [**PHP**](https://www.php.net/downloads.php) - versão 8.3 ou superior
- [**Composer**](https://getcomposer.org/download/) - versão 2.7 ou superior
- [**MySQL**](https://dev.mysql.com/downloads/) (Opcional)

Obs: Você pode utilizar outro database caso deseje, basta alterar as configurações do `.env`.

### Configurando o Ambiente

1. Primeiro clone o repositório e navegue até o diretório
```bash
git clone https://github.com/ataidefcjr/coodesh-backend-dictionary-test.git
cd coodesh-backend-dictionary-test/dictionary-coodesh
```

2. Renomeie o arquivo `.env.example` para `.env` e faça as alterações necessárias, colocando suas credenciais de acesso ao Banco de Dados.
```bash
cp .env.example .env
```
Por exemplo:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dictionary
DB_USERNAME=root
DB_PASSWORD=
```

3. Instale as dependẽncias do Composer e NPM
```bash
composer install
```


4. Gere uma chave de acesso, a chave será automaticamente escrita no `.env`
```bash
php artisan key:generate
```

5. Migration e Seed - Este comando vai criar as tabelas no seu banco de dados e fazer o seed das palavras.
```bash
php artisan migrate --seed
```

6. Rode o servidor de desenvolvimento
```bash
php artisan serve
```

7. Com o servidor de desenvolvimento rodando voce é capaz de [Acessar a documentação](#acesse-a-documentação) para obter maiores informações sobre os end-points.


## Docker Setup

O Docker é utilizado para facilitar o desenvolvimento e a execução do projeto em containers. Siga as etapas abaixo para configurar o ambiente de desenvolvimento no Docker.

### Pré-requisitos
- [**Git**](https://git-scm.com/downloads)
- [**Docker**](https://docs.docker.com/get-docker/) - versão 20.10.0 ou superior
- [**Docker Compose**](https://docs.docker.com/compose/install/) - versão 1.27.0 ou superior

### Configuração do Ambiente


1. Primeiro clone o repositório e navegue até o diretório
```bash
git clone https://github.com/ataidefcjr/coodesh-backend-dictionary-test.git
cd coodesh-backend-dictionary-test/dictionary-coodesh
```

2. Renomeie o arquivo `.env.example` para `.env`
```bash
cp .env.example .env
```

3. Agora precisamos construir e subir a imagem Docker
```bash
docker compose build
```
```bash
docker compose up -d 
```

4. Nesse passo garantimos que as dependencias sejam atualizadas, geramos uma chave e configuramos o banco de dados, nesse passo as palavras são importadas automaticamente com o argumento --seed.
```bash
docker compose exec laravel.test composer update
```
```bash
docker compose exec laravel.test php artisan key:generate
```
```bash
docker compose exec laravel.test php artisan migrate:fresh --seed
```

5. Agora damos as permissões corretas para garantir que não tenhamos erros de permissão no diretório de cache e storage
```bash
docker compose exec laravel.test chown -R sail:1000 /var/www/html/storage /var/www/html/bootstrap/cache
```
```bash
docker compose exec laravel.test chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```
6. Reiniciamos os containeres para garantir que todas as alterações aplicadas entrem em vigor:
```bash
docker compose restart
```

7. Com o servidor de desenvolvimento rodando voce é capaz de [Acessar a documentação](#acesse-a-documentação) para obter maiores informações sobre os end-points.

## Acesse a documentação

- Com a aplicação em funcionamento, basta acessar o link `http://localhost:8000/docs/api` para saber maiores informações sobre os end-points.

## Sobre o desafio

Decidi fazer a API utilizando o framework `Laravel` pois o mesmo é bastante robusto, tem alta flexibilidade e uma comunidade grande. Além disso existem muitos recursos integrados como autenticação, segurança e migrations, que deixam o desenvolvimento mais seguro, rápido e eficiente.

Comecei fazendo um desenho de como seria estruturado o banco de dados.

Decidi fazer alguns ajustes na tabela `users` para usar UUID em vez do id número incremental para maior segurança. Para a geração dos UUID's, utilizei a biblioteca `Ramsey`.

Utilizei o banco de dados `MySQL` devido à sua ampla utilização no mercado, desempenho e à minha familiaridade com ele.

Em sequência parti para fazer os `Controllers`, que contêm a lógica da aplicação, cada Controller foi desenvolvido com base nos casos de uso definidos e ao padrão de respostas fornecido.

Assim que atendi a todos os casos de uso, precisei aprender a utilizar o `Sanctum`, que proporciona uma maneira simples de gerenciar as autenticações via tokens. A integração foi surpreendemente simples, bastou alterar algumas linhas de código e estava tudo funcionando como deveria.

Na rota `/user/me`, além dos dados básicos do usuário, decidi incluir os quatro últimos itens no histórico de buscas e favoritos. Essa informação pode ajudar o front-end com menos requisições, caso necessário, essa funcionalidade pode ser facilmente ajustada ou removida.

A exigência do teste foi que as respostas da API fossem padronizadas com os `status` `200` para sucesso e `400` para erros. Para atender a isso, modifiquei a configuração em `bootstrap/app.php`, garantindo que todas as exceções gerassem uma resposta padronizada com a mensagem de erro `'Woops! Something went wrong!'`.

A obrigatoriedade de importação de palavras foi atendida com o comando personalizado `php artisan import:words`. Esse comando já foi implementado dentro do Database Seeder, o que significa que, ao rodar a migração com `php artisan migrate:fresh --seed`, as palavras serão automaticamente importadas. Não sendo necessário rodar o comando de importação separadamente, já que ele é invocado automaticamente durante o processo de seed.

Como estava fazendo ponto a ponto das instruções, a última obrigatoriedade do teste era a utilização do `cache`, então precisei refazer uma parte do controller responsável pela busca à API externa, o que também achei que seria um bicho de sete cabeças, pois, nunca tinha feito antes. 

Decidi por guardar o `cache` por 30 dias, o que pode ser facilmente ajustado caso necessário, o desempenho foi bastante satisfatório, economizando chamadas externas e diminuindo o tempo de resposta.

Logo que terminei parti para efetuar os diferenciais que foram: 
* Docker
* Documentação

A documentação foi uma das partes mais difíceis para mim, (que também foi a minha primeira vez fazendo) pois o `Scramble` apesar de automatizar quase tudo, perdi muito tempo procurando sobre todas as funcionalidades e como alterar algumas coisas que haviam sido automatizadas de forma errada, um dos erros que não consegui sanar foi a padronização das respostas, o mesmo apresenta diversos códigos de status que não ocorrem.

Apesar de todas as dificuldades que enfrentei, achei bastante divertido e pude aprender muitas coisas, pois as dificuldades foram facilmente sanadas pesquisando a `documentação` das tecnologias usadas, entre elas Laravel, Ramsey, Sanctum, Scramble e Docker.